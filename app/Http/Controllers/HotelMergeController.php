<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\new_user;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use App\Models\hotel;
use App\Models\room;
use App\Models\hotelbook;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\City;
use App\Models\Rooms; 
use App\Models\Address;
use App\Models\ContactNumbers;
use App\Models\StateCountyProv;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use App\Models\otherImage;
use Illuminate\Support\Facades\Cache;

class HotelMergeController extends Controller
{

    public function filterapi(Request $request)
    {
      
           try {
            $countryCode = $request->input('countryCode');
            $cityCode = $request->input('cityCode');
            $hotelName = $request->input('hotelName');
            $Rooms = $request->input('Rooms', []);
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $authToken = $request->header('Sabretoken');
        
            // Validate required fields
            if (!$authToken) {
                return response()->json(['error' => 'Sabre token is missing'], 400);
            }
            if (!$startDate || !$endDate) {
                return response()->json(['error' => 'StartDate and EndDate are required'], 400);
            }
            if (!is_array($Rooms) || empty($Rooms)) {
                return response()->json(['error' => 'Invalid Rooms format or empty'], 400);
            }
        
            // Process Rooms array safely
            $roomsArray = [];
            foreach ($Rooms as $index => $room) {
                $roomsArray[] = [
                    'Index' => $index + 1,
                    'Adults' => $room['Adults'] ?? 1,
                    'Children' => $room['Children'] ?? 0,
                ];
            }
                
                $headers = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Conversation-ID' => '2021.01.DevStudio',
                    'Authorization' => $authToken,
                    // 'Authorization' => 'Bearer T1RLAQJCRVCAoLz/WUcIi1/WNjzHb4nLo662De/+iWKFPGTzeBDoqlhvSWE9tfSQLWutj77BAADQ9UCbsdLbUAC4ZH9z3NM8h0pcHJR1jtIkQDJAOxvOgLXmPe/4VKxOVQ08lOCfxZTQ8tVjYMRCG3qINllleY1OdiO79Eiv8Vva8oEu2COY/7OG0wUTsC+UVOBi4FCh7ExH51UIhYA6TTvhbKXXQZPPce2T6gHi4+o3GMR7fhNPHpmtC+8LoL3yyoXAfHYQCueySxNyBYWOmJkqQIhw4CGq6en2mlef5SIt151fy5sotmHkcQ4Qpd6ulK7ERCkr+P9TOiy+PK/f3mD+LUxuxbNS+g**',
                    'Cookie' => 'incap_ses_33_2768617=xztsd21vp3qqweQhiz11ABxLgmcAAAAA3HxbNsuz0caM8hM6OmNYmg==; visid_incap_2768617=jcx4fKvUSdGUx0ZiZYKnwycXSGcAAAAAQUIPAAAAAADjcRGikEBwkueWK2j/c46g',
                ];

            $body = [
                'GetHotelAvailRQ' => [
                    'SearchCriteria' => [
                        'OffSet' => 1,
                        'SortBy' => 'TotalRate',
                        'SortOrder' => 'ASC',
                        'PageSize' => 20,
                        'TierLabels' => false,
                        'GeoSearch' => [
                            'GeoRef' => [
                                'Radius' => 20,
                                'UOM' => 'MI',
                                'RefPoint' => [
                                    'Value' => 'DFW',
                                    'ValueContext' => 'CODE',
                                    'RefPointType' => '6',
                                ],
                            ],
                        ],
                        'RateInfoRef' => [
                            'ConvertedRateInfoOnly' => false,
                            'CurrencyCode' => 'USD',
                            'BestOnly' => '2',
                            'PrepaidQualifier' => 'IncludePrepaid',
                            'StayDateRange' => [
                                'StartDate' => $startDate,
                                'EndDate' => $endDate,
                            ],
                            'Rooms' => [
                                'Room' => $roomsArray,
                            ],
                            'InfoSource' => '100,110,112,113',
                        ],
                        'HotelPref' => [
                            'SabreRating' => [
                                'Min' => '3',
                                'Max' => '5',
                            ],
                        ],
                        'ImageRef' => [
                            'Type' => 'MEDIUM',
                            'LanguageCode' => 'EN',
                        ],
                    ],
                ],
            ]; 
            
            $response = Http::withHeaders($headers)
            ->post('https://api.platform.sabre.com/v3.0.0/get/hotelavail', $body);
    
        if ($response->successful()) {
            // Extract data from the first response
            $responseData1 = $response->json();
    
            if (!isset($responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'])) {
                Log::error('Unexpected structure in first API response', ['response' => $responseData1]);
                return response()->json(['message' => 'Unexpected response structure from first external API'], 500);
            }
    
             $responseData1 = $responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];
            
            $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode, $hotelName) {
                $countryMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
                    $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode;
    
                $cityMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
                    $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode;
    
                $hotelNameMatch = isset($hotel['HotelInfo']['HotelName']) &&
                    $hotel['HotelInfo']['HotelName'] === $hotelName;
    
                return $countryMatch || $cityMatch || $hotelNameMatch;
            });
         
            // $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode, $hotelName, $Rooms, $startDate, $endDate) {
            //     // Match the country code
            //     $countryMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
            //                     $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode;
                
            //     // Match the city code
            //     $cityMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
            //                  $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode;
                
            //     // Match the hotel name
            //     $hotelNameMatch = isset($hotel['HotelInfo']['HotelName']) &&
            //                       $hotel['HotelInfo']['HotelName'] === $hotelName;
            
            //     // Room matching logic remains the same
            //     $roomMatch = true; // Default to true unless conditions don't match
            //     if (isset($hotel['Rooms']['Room']) && is_array($hotel['Rooms']['Room'])) {
            //         foreach ($Rooms as $room) {
            //             $roomFound = false;
            //             foreach ($hotel['Rooms']['Room'] as $hotelRoom) {
            //                 if (($hotelRoom['Adults'] ?? 0) === $room['Adults'] && ($hotelRoom['Children'] ?? 0) === $room['Children']) {
            //                     $roomFound = true;
            //                     break;
            //                 }
            //             }
            
            //             if (!$roomFound) {
            //                 $roomMatch = false;
            //                 break;
            //             }
            //         }
            //     } else {
            //         $roomMatch = false; // If Rooms are not set or invalid, exclude this hotel
            //     }
            
            //     // Date matching logic remains the same
            //     $dateMatch = false;
            //     if (isset($hotel['HotelRateInfo']['RateInfos']['RateInfo']) && is_array($hotel['HotelRateInfo']['RateInfos']['RateInfo'])) {
            //         foreach ($hotel['HotelRateInfo']['RateInfos']['RateInfo'] as $rateInfo) {
            //             $hotelStartDate = $rateInfo['StartDate'] ?? null;
            //             $hotelEndDate = $rateInfo['EndDate'] ?? null;
            
            //             // Convert the dates to comparable format (YYYY-MM-DD)
            //             if ($hotelStartDate && $hotelEndDate) {
            //                 $hotelStartDate = strtotime($hotelStartDate);
            //                 $hotelEndDate = strtotime($hotelEndDate);
            //                 $requestedStartDate = strtotime($startDate);
            //                 $requestedEndDate = strtotime($endDate);
            
            //                 // Check if the requested dates overlap with the hotel availability
            //                 if (($requestedStartDate <= $hotelEndDate) && ($requestedEndDate >= $hotelStartDate)) {
            //                     $dateMatch = true;
            //                     break; // No need to check further rateInfos if one matches
            //                 }
            //             }
            //         }
            //     } else {
            //         $dateMatch = false; // If RateInfo is not set or invalid, exclude this hotel
            //     }
            
            //     return ($countryMatch || $cityMatch || $hotelNameMatch) && $roomMatch && $dateMatch;
            // });


            
            // Transform the filtered hotels
            $transformedHotels = array_map(function ($hotel) {
                // Access the first RateInfo element to get CurrencyCode
                $currencyCode = null;
                if (isset($hotel['HotelRateInfo']['RateInfos']['RateInfo'][0]['CurrencyCode'])) {
                    $currencyCode = $hotel['HotelRateInfo']['RateInfos']['RateInfo'][0]['CurrencyCode'];
                    $startDate = $hotel['HotelRateInfo']['RateInfos']['RateInfo'][0]['StartDate'];
                    $endDate = $hotel['HotelRateInfo']['RateInfos']['RateInfo'][0]['EndDate'];
                    
                }
            
                return [
                    'HotelCode' => $hotel['HotelInfo']['HotelCode'] ?? null,
                    'hotel' => $hotel['HotelInfo']['HotelName'] ?? null,
                    'rating' => $hotel['HotelInfo']['SabreRating'] ?? null,
                    // 'distance' => $hotel['HotelInfo']['Distance'] ?? null,
                    'latitude' => $hotel['HotelInfo']['LocationInfo']['Latitude'] ?? null,
                    'longitude' => $hotel['HotelInfo']['LocationInfo']['Longitude'] ?? null,
                    'city' => $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] ?? null,
                    'country' => $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] ?? null,
                    'address' => $hotel['HotelInfo']['LocationInfo']['Address']['AddressLine1'] ?? null,
                    'currency' => $currencyCode,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
           
    
                    'facilities' => is_array($hotel['HotelInfo']['Amenities']['Amenity'])
                        ? array_map(function ($amenity) {
                            return $amenity['Description'] ?? '';
                        }, $hotel['HotelInfo']['Amenities']['Amenity'])
                        : array_filter(explode(',', $hotel['HotelInfo']['Amenities']['Amenity'] ?? '')),
    
    
                    // 'facilities' => [
                    // array_map(function($amenity) {
                    //     return $amenity['Description'] ?? '';  
                    // }, $hotel['HotelInfo']['Amenities']['Amenity'] ?? []),
    
                    'Rooms' => array_map(function ($room) {
                        return [
                            'RoomIndex' => $room['RoomIndex'] ?? null,
                            'room_type' => $room['RoomType'] ?? null,
                            'NonSmoking' => $room['NonSmoking'] ?? null,
                            'GuestRoomInfo' => $room['GuestRoomInfo'] ?? null,
                            'bed_types' => array_map(function ($bed) {
                                return [
                                    'code' => $bed['Code'] ?? null,
                                    'count' => $bed['Count'] ?? null,
                                ];
                            }, $room['BedTypes']['BedType'] ?? []),
                            'room_description' => [
                                'name' => $room['RoomDescription']['Name'] ?? null,
                                'text' => $room['RoomDescription']['Text'] ?? null,
                            ],
                    
    
                                'facilities' => array_map(function ($amenity) {
                                return $amenity['Description'] ?? '';  
                            }, $room['Amenities']['Amenity'] ?? []),  
                        ];
                                    
                        }, $hotel['HotelRateInfo']['Rooms']['Room'] ?? []),
                    
                            // 'Image' => [
                            //     'images' => $hotel['HotelImageInfo']['ImageItem']['Image']['Url'] ?? null,
                            //     'Type' => $hotel['HotelImageInfo']['ImageItem']['Image']['Type'] ?? null,
                            // ],
                            "images"=>[
                               $hotel['HotelImageInfo']['ImageItem']['Image']['Url'] ?? null,
                            ]
                
                
                            
                   
                ];
            }, $filteredHotels);
    
            // Second API request
            $bearerToken = $request->header('Authorization');
            if (!$bearerToken) {
                return response()->json(['message' => 'The authorization token is required.'], 400);
            }
    
            $response2 = Http::timeout(5)->withHeaders([
                'Authorization' => $bearerToken,
            ])->get('https://hotel.aotrek.net/api/auth/show_hotel');
    
            if ($response2->successful()) {
                $responseData2 = $response2->json();
    
                $filteredHotels1 = array_filter($responseData2['data'], function ($hotel) use ($countryCode, $cityCode, $hotelName) {
                    // Convert inputs to arrays if they are not already arrays
                    $countryCodes = is_array($countryCode) ? $countryCode : [$countryCode];
                    $cityCodes = is_array($cityCode) ? $cityCode : [$cityCode];
                    $hotelNames = is_array($hotelName) ? $hotelName : [$hotelName];
                
                    // Check if hotel matches ANY condition (OR logic)
                    $matchesCountry = isset($hotel['country']) && in_array($hotel['country'], $countryCodes);
                    $matchesCity = isset($hotel['city']) && in_array($hotel['city'], $cityCodes);
                    $matchesHotelName = isset($hotel['hotel']) && in_array($hotel['hotel'], $hotelNames);
                
                    // Return true if ANY of the conditions match
                    return $matchesCountry || $matchesCity || $matchesHotelName;
                });
                
    
                $combinedData = [
                    'data' => array_merge($transformedHotels)
                ];
                
                return response()->json($combinedData);
            } else {
                Log::error('Second API request failed', [
                    'status' => $response2->status(),
                    'body' => $response2->body(),
                ]);
                return response()->json(['message' => 'Error fetching data from second external API'], $response2->status());
            }
        } else {
            Log::error('First API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json(['message' => 'Error fetching data from first external API'], $response->status());
        }
    } catch (\Exception $error) {
        Log::error('Exception occurred', [
            'message' => $error->getMessage(),
            'trace' => $error->getTraceAsString(),
        ]);
        return response()->json(['message' => 'Error fetching data from external API', 'error' => $error->getMessage()], 500);
    }
    
    }
    

}