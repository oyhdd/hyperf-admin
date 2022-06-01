<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;

use Oyhdd\Admin\Exception\RuntimeException;
use Oyhdd\Admin\Service\ExcelService;

trait ExportTrait
{
    /**
     * Export data to excel from model.
     * 
     * @param int       $is_all
     * @param int       $is_page
     * @param string    $id
     * @param int       $_perPage
     * @param int       $_page
     */
    public function export(array $params, array $select = ['*'])
    {
        $is_all = intval($params['is_all'] ?? 0);
        $is_page = intval($params['is_page'] ?? 0);
        $id = $params['id'] ?? '';
        $id = explode(',', $params['id'] ?? '');
        $perPage = intval($params['_perPage'] ?? 10);
        $page = intval($params['_page'] ?? 1);

        if ($is_all) {
            $models = $this->query()->get($select);
        } elseif ($is_page) {
            $models = $this->query()->forPage($page, $perPage)->get($select);
        } else {
            $models = $this->query()->select($select)->find($id);
        }

        $data = $models->toArray();
        if (empty($data)) {
            throw new RuntimeException(trans('admin.no_query_results'));
        }

        $title = [];
        foreach ($data[0] as $key => $value) {
            $title[] = trans($this->getTable() . '.fields.' . $key);
        }

        $excelService = new ExcelService();
        return $excelService->setHeader($title)->addData($data)->saveToBrowser(trans($this->getTable() . '.title') . '-' . date("YmdHis"));
    }
}
