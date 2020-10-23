<?php

namespace App\Controllers;

use App\Models\Grades;
use App\Models\Students;

class StudentsController {

    public function getStudent(array $params): void {
        $student   = Students::find(['id' => $params[0]]);
        $grades    = $this->getGrades($student->id);
        $gradesNum = count($grades);
        $avgGrade  = $this->getAvgGrade($grades);
        $isPassed  = false;
        $dataType  = '';

        if($gradesNum >= 1 && $gradesNum <= 4){
            if($student->board_id == 1 && $avgGrade >= 7){
                $dataType = 'json';
                $isPassed = true;
            }
            elseif($student->board_id == 2) {
                $dataType = 'xml';
                if($gradesNum > 2){
                    $grades = $this->removeLowest($grades);
                }
                if(max($grades) > 8){
                    $isPassed = true;
                }
                else {
                    $isPassed = false;
                }
            }
            else {
                $isPassed = false;
            }
        }
        else {
            $isPassed = false;
        }

        $data = [
            'student_id'    => $student->id,
            'first_name'    => $student->first_name,
            'last_name'     => $student->last_name,
            'grades'        => $grades,
            'average_grade' => $avgGrade,
            'result'        => $isPassed ? 'Pass' : 'Fail'
        ];

        $this->returnData($data, $dataType);
    }

    private function returnData($data, $dataType) {
        switch($dataType) {
            case 'json':
                echo json_encode($data);
                break;
            case 'xml':
                echo '<pre>' . htmlspecialchars($this->arrayToXml($data, '<student></student>')) . '</pre>';
                break;
        }
    }

    private function removeLowest(&$grades) {
        sort($grades, SORT_NUMERIC);
        array_shift($grades);

        return $grades;
    }

    private function arrayToXml($array, $rootElement = null, $xml = null) {
        $_xml = $xml;

        if($_xml === null){
            $_xml = new \SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        }

        foreach($array as $k => $v) {

            if(is_array($v)){
                $this->arrayToXml($v, $k, $_xml->addChild($k));
            }

            else {
                $_xml->addChild($k, $v);
            }
        }

        return $_xml->asXML();
    }

    private function getGrades(int $studentId): array {
        $grades = Grades::find(['student_id' => $studentId]);

        $allGrades = [];
        foreach($grades as $grade) {
            $allGrades[] = $grade->grade_value;
        }

        return $allGrades;
    }

    private function getAvgGrade(array $grades): int {
        $avg = 0;

        if(count($grades) > 0){
            $avg = array_sum($grades) / count($grades);
        }

        return $avg;
    }


}