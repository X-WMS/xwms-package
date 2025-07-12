<?php

namespace LaravelShared\Core\Services;

class WorkspaceService
{
    protected $models;
    protected $idName = "xwms_id";

    // ------------------------------------------------------
    // --------- COMPANIES
    // ------------------------------------------------------

    public function getBladeCompanies(int $identifier)
    {
        $CompanyService = new CompanyService;
        $Companies = $CompanyService->getData($identifier, 'company', false);

        return $Companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->name
            ];
        })->toArray();
    }

    public function models($model)
    {
        return $this->models[$model];
    }


    // ------------------------------------------------------
    // ------------------------------------------------------
    // --------- DYNAMIC CRUD
    // ------------------------------------------------------



    // ------------------------------------------------------
    // --------- GET THE DATA PROTECTED
    // ------------------------------------------------------

    protected function getData(int $identifier, string $type, $formatted = true)
    {
        $model = $this->getModel($type);
        $data = $model->where([$this->idName => $identifier])->get();

        return $formatted === true ? $data->toArray() : $data;
    }

    protected function getDataSingle(int $identifier, int $id, string $type, $formatted = true)
    {
        $model = $this->getModel($type);
        $data = $model->where([$this->idName => $identifier, 'id' => $id])->get();

        return $formatted === true ? $data->toArray() : $data;
    }

    // ------------------------------------------------------
    // --------- STORE THE DATA PROTECTED
    // ------------------------------------------------------

    protected function storeData(string $type, array $data)
    {
        $model = $this->getModel($type);
        $row = $model->create($data);

        if (!$row) {
            throw new \Exception("Could not create the $type for some reason. Try again.");
        }

        $row = $model->find($row->id);

        if (!$row || !$row->id) {
            throw new \Exception("Could not verify the created $type. Try again.");
        }

        return $row;
    }
    

    // ------------------------------------------------------
    // --------- UPDATE THE DATA PROTECTED
    // ------------------------------------------------------

    protected string $serviceName;
    protected array $serviceTypes = [];
    protected array $customServiceData = [];

    public function updateCrudData(int $identifier, string $type, int $id, array $data): array
    {
        return $this->updateData($identifier, $type, $id, $data);
    }

    protected function updateData(int $identifier, string $type, int $id, array $data): array
    {
        $record = $this->getRecord($identifier, $type, $id);
        $data = $this->processCustomServiceData($identifier, $type, $id, $data);
        $record->update($data);
        $recordName = $this->getRecordName($record);

        return [
            'status' => 'success',
            'message' => "You have successfully updated {$this->serviceName}: " . ($recordName ?? "Unnamed Record"),
            'data' => $data
        ];
    }

    // ------------------------------------------------------
    // --------- DELETE THE DATA PROTECTED
    // ------------------------------------------------------

    public function deleteCrudData(int $identifier, string $type, int $id): array
    {
        return $this->deleteData($identifier, $type, $id);
    }

    protected function deleteData(int $identifier, string $type, int $id): array
    {
        $record = $this->getRecord($identifier, $type, $id);
        $recordName = $this->getRecordName($record);
        $record->delete();

        return [
            'status' => 'success',
            'message' => "You have successfully deleted {$this->serviceName}: " . ($recordName ?? "Unnamed Record"),
        ];
    }

    // ------------------------------------------------------
    // --------- CRUD HELPERS
    // ------------------------------------------------------

    private function getModel($type)
    {
        if (!isset($this->models[$this->serviceTypes[$type]])) {
            throw new \Exception("Invalid {$this->serviceName} type: $type");
        }

        return $this->models[$this->serviceTypes[$type]];
    }

    private function getRecord(int $identifier, string $type, int $id)
    {
        $model = $this->getModel($type);
        $record = $model->where(['id' => $id, $this->idName => $identifier])->first();

        if (!$record) {
            throw new \Exception("This {$this->serviceName} $type was not found.");
        }

        return $record;
    }

    private function getRecordName($record)
    {
        $possibleNames = [
            $record->name ?? null,
            $record->username ?? null,
            $record->setting_name ?? null,
            $record->title ?? null,
            $record->email ?? null,
        ];

        $recordName = collect($possibleNames)->first(fn($name) => !is_null($name));
        return $recordName;
    }

    protected function processCustomServiceData(int $identifier, string $type, int $id, array $data): array
    {
        return isset($this->customServiceData[$type]) 
            ? $this->{$this->customServiceData[$type]}($identifier, $id, $data) 
            : $data;
    }

    // ------------------------------------------------------
    // --------- FORMATTED TYPES
    // ------------------------------------------------------

    protected function formatRows($rows, callable $callback)
    {
        if (!$rows) return null;
        if (empty($rows->toArray())) return [];
        $arrayRow = $rows->toArray();

        if (is_array($arrayRow) && count($arrayRow) > 0 && array_reduce($arrayRow, fn($carry, $item) => $carry && is_array($item), true)) {
            $rows = collect($rows);
        }else{
            $rows = collect([$rows]);
        }

        return $rows->map($callback)->toArray();
    }
    
    protected function getFormattedCompanies($rows)
    {
        return $this->formatRows($rows, fn($row) => [
            'id' => $row->id,
            'name' => $row->name,
            'email' => $row->email,
            'phone' => $row->phone,
            'address' => $row->address
        ]);
    }
    
    
    protected function getFormattedTags($rows)
    {
        return $this->formatRows($rows, fn($row) => [
            'id' => $row->id,
            'company' => $this->getFormattedCompanies($row['company']),
            'title' => $row['title'],
            'rate' => $row['rate'],
            'color' => $row['color']
        ]);
    }

    protected function getFormattedBreaks($rows)
    {
        return $this->formatRows($rows, fn($row) => [
            'id' => $row->id,
            'start_time' => $row->start_time,
            'end_time' => $row->end_time,
            'break_duration' => $row->break_duration
        ]);
    }

    protected function hsrImg(string $img): string
    {
        return "hsr::img::{$img}";
    }

    protected function hsrTitle(string $title): string
    {
        return "hsr::title::" . str_replace(' ', '_', $title);
    }

    protected function hsrStatus(string $status): string
    {
        return "hsr::status::{$status}";
    }

    protected function hsrDuration(int $ms): string
    {
        return "hsr::duration::{$ms}";
    }

    protected function hsrClosable(bool $bool): string
    {
        return "hsr::closable::" . ($bool ? 'true' : 'false');
    }

    protected function hsrIcon(string $icon): string
    {
        return "hsr::icon::{$icon}";
    }

    // Voeg eventueel meer toe zoals:
    protected function hsrPosition(string $pos): string
    {
        return "hsr::position::{$pos}";
    }

}
