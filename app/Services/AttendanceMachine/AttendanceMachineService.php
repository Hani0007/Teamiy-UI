<?php

namespace App\Services\AttendanceMachine;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceMachineService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://mqtt.openapi.eelpw.com/openapi/open/api/gateway';
    }

    /**
     * Fetch attendance records from the machine
     */
    public function fetchAttendanceRecord(string $deviceSn, $startDate, $endDate)
    {
        try {
            $payload = [
                'deviceSn' => $deviceSn,
                'interType' => '42001',
                'content' => json_encode([
                    "deviceSn" => $deviceSn,
                    "startNoteTime" => $startDate,
                    "endNoteTime" => $endDate,
                    "pageNo" => 1,
                    "pageSize" => 500
                ]),
            ];

            $response = Http::asMultipart()
                ->post($this->baseUrl, $payload);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => 'API request failed',
                    'error'   => $response->body(),
                ];
            }

            return [
                'success' => true,
                'data'    => $response->json(),
            ];

        } catch (\Exception $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    public function addEmployee(string $deviceSn, $employee)
    {
        try {
            $payload = [
                'deviceSn'  => $deviceSn,
                'interType' => '32001',
                'content'   => json_encode([
                    'deviceSn' => $deviceSn,
                    'employeeDetailBeanList' => [
                        [
                            "employeeId"        => $employee->employee_code,
                            "employeeName"      => $employee->name,
                            "employeePhotoWay"  => "path",
                            "employeePhoto"     => asset('uploads/user/avatar/' . $employee->avatar),
                            "employeeIc"        => $employee->nfc_card
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES),
            ];

            $response = Http::asMultipart()
                ->post($this->baseUrl, $payload);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => 'API request failed',
                    'error'   => $response->body(),
                ];
            }
            $apiResponse = $response->json();

            if($apiResponse['code'] !== 98)
            {
                $errorLog = $this->checkErrorLog($deviceSn, $employee);

                if (!empty($errorLog['data']) && ($errorLog['data']['status'] === 'FAILED')) {
                    Log::info(json_encode([
                        'success' => false,
                        'message' => $errorLog['data']['errorMsg'] ?? 'Unknown error'
                    ]));

                    return [
                        'success' => false,
                        'message' => $errorLog['data']['errorMsg'] ?? 'Unknown error'
                    ];
                }

                if(($errorLog['data']['status'] ?? '') !== 'FAILED' && $errorLog['data']['employeeId'] === $employee->employee_code)
                {
                    return [
                        'synced'  => true
                    ];
                }
                else{
                    return [
                        'synced'  => false
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $apiResponse['message'],
            ];

        } catch (\Exception $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    private function checkErrorLog(string $deviceSn, $employee)
    {
        try {
            $payload = [
                'deviceSn'  => $deviceSn,
                'interType' => '32004',
                'content'   => json_encode([
                    'deviceSn'   => $deviceSn,
                    'employeeId' => $employee->employee_code,
                    'optType'    => 'employee_add',
                ], JSON_UNESCAPED_SLASHES),
            ];

            $response = Http::asForm()->post($this->baseUrl, $payload);

            return $response->json();

        } catch (\Exception $ex) {
            return [
                'success' => false,
                'data' => [
                    'status' => 'FAILED',
                    'errorMsg' => $ex->getMessage()
                ]
            ];
        }
    }

    public function checkMachineStatus($deviceSn)
    {
        try {
            $payload = [
                'deviceSn'  => $deviceSn,
                'interType' => '41001',
                'content'   => json_encode([
                    'deviceSn' => $deviceSn
                ]),
            ];

            $response = Http::asMultipart()->post($this->baseUrl, $payload);
            $response = $response->json();

            if($response['code'] === 200 && $response['data'] === 'ON-LINE')
            {
                return true;
            }

            return false;

        } catch (\Exception $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }
}



