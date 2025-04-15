<?php 

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\User\Entity\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\DTOs\QueryDto;
use Illuminate\Support\Collection;

class BaseRepository
{
    protected $helper;


    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    
    public function fetchAllAssociative(string $sql): array
    {
        try {
            $query = DB::select($sql);
            $query = json_decode(json_encode($query), true); // stdClass → array dönüşümü

            if (!empty($query)) {
                return [
                    'status' => $this->helper::EXIST,
                    'data' => $query
                ];
            }

            return [
                'status' => $this->helper::NOT_EXIST,
                'data' => null
            ];
        } catch (\Exception $exception) {
            // Örneğin özel loglama helper'ınız varsa:
            /*$this->helper->gelfOutput([
                'request' => $sql,
                'exception' => $exception->getMessage()
            ], false, 3);*/

            return [
                'status' => $this->helper::SQL_EXCEPTION,
                'data' => []
            ];
        }
    }


    public function fetchAllAssociativeDTO(QueryDto|Collection|array|Model|string $input): array
    {
        try {
            // 1. Eğer DTO geldiyse
            if ($input instanceof QueryDto) {
                $sql = $input->sql;

            // 2. Eğer bir Collection veya array geldiyse
            } elseif ($input instanceof Collection || is_array($input)) {
                $data = is_array($input) ? $input : $input->toArray();

                return [
                    'status' => empty($data) ? $this->helper::NOT_EXIST : $this->helper::EXIST,
                    'data' => $data,
                ];

            // 3. Eğer bir Model instance geldiyse
            } elseif ($input instanceof Model) {
                $data = $input->toArray();

                return [
                    'status' => empty($data) ? $this->helper::NOT_EXIST : $this->helper::EXIST,
                    'data' => $data,
                ];

            // 4. Eğer doğrudan SQL geldiyse
            } elseif (is_string($input)) {
                $sql = $input;

            // 5. Geçersiz veri tipi
            } else {
                return [
                    'status' => $this->helper::SQL_EXCEPTION,
                    'data' => [],
                    'error' => 'Invalid input type.'
                ];
            }

            // Eğer SQL varsa çalıştır
            if (isset($sql)) {
                $query = DB::select($sql);
                $query = json_decode(json_encode($query), true); // stdClass to array

                return [
                    'status' => empty($query) ? $this->helper::NOT_EXIST : $this->helper::EXIST,
                    'data' => $query,
                ];
            }

        } catch (\Exception $exception) {
            $this->helper->gelfOutput([
                'request' => $input,
                'exception' => $exception->getMessage()
            ], false, 3);

            return [
                'status' => $this->helper::SQL_EXCEPTION,
                'data' => [],
                'error' => $exception->getMessage()
            ];
        }
    }

}
