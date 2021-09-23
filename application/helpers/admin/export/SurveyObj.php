<?php

class SurveyObj
{
    /**
     * @var int
     */
    public $id;

    /**
     * Answer, codes, and full text to the questions.
     * This is used in conjunction with the fieldMap to produce
     * some of the more verbose output in a survey export.
     * array[recordNo][columnName]
     *
     * @var int[]|string[]|mixed[]
     */
    public $answers = array();

    /**
     * The fieldMap as generated by createFieldMap(...).
     * @var array[]mixed
     */
    public $fieldMap;

    /**
     * The groups in the survey.
     *
     * @var int[]|string[]|mixed[]
     */
    public $groups;

    /**
     * info about the survey
     *
     * @var array
     */
    public $info;

    /**
     * The questions in the survey.
     *
     * @var int[]|string[]|mixed[]
     */
    public $questions;


    /**
     * When relevant holds the available fields from the survey participants table
     *
     * @var array[fieldname][localised description]
     */
    public $tokenFields = array();

    /**
     * Stores the responses to the survey in a two dimensional array form.
     * array[recordNo][fieldMapName]
     *
     * @var int[]|string[]|mixed[]
     */
    public $responses;

    /**
     *
     * @var int[]|string[]|mixed[]
     */
    public $languageSettings;



    /**
     * Returns the full answer for the question that matches $fieldName
     * and the answer that matches the $answerCode.  If a match cannot
     * be made then false is returned.
     *
     * The name of the variable $answerCode is not strictly an answerCode
     * but could also be a comment entered by a participant.
     *
     * @param string $fieldName
     * @param string $answerCode
     * @param Translator $translator
     * @param string $sLanguageCode
     * @return string (or false)
     */
    public function getFullAnswer($fieldName, $answerCode, Translator $translator, $sLanguageCode)
    {
        $fullAnswer = null;
        $fieldType = $this->fieldMap[$fieldName]['type'];
        $question = $this->fieldMap[$fieldName];
        $questionId = $question['qid'];
        $answer = null;
        if ($questionId) {
            $answers = $this->getAnswers($questionId);
            if (isset($answers[$answerCode])) {
                $answer = $answers[$answerCode];
            }
        }

        //echo "\n$fieldName: $fieldType = $answerCode";
        switch ($fieldType) {
            case Question::QT_K_MULTIPLE_NUMERICAL_QUESTION:
            case Question::QT_N_NUMERICAL:
                $fullAnswer = $answerCode;
                if (trim($fullAnswer) !== '') {
                    // SQL DECIMAL
                    if ($fullAnswer[0] === ".") {
                        $fullAnswer = "0" . $fullAnswer;
                    }
                    if (strpos($fullAnswer, ".") !== false) {
                        $fullAnswer = rtrim(rtrim($fullAnswer, "0"), ".");
                    }
                    $qidattributes = QuestionAttribute::model()->getQuestionAttributes($questionId);
                    if (isset($qidattributes['num_value_int_only']) && $qidattributes['num_value_int_only']) {
                        $fullAnswer = number_format($fullAnswer, 0, '', '');
                    }
                }
                break;

            case Question::QT_R_RANKING_STYLE:   //RANKING TYPE
                $fullAnswer = $answer;
                break;

            case Question::QT_1_ARRAY_MULTISCALE:   //Array dual scale
                if (mb_substr($fieldName, -1) == 0) {
                    $answers = $this->getAnswers($questionId, 0);
                } else {
                    $answers = $this->getAnswers($questionId, 1);
                }
                if (array_key_exists($answerCode, $answers)) {
                    $fullAnswer = $answers[$answerCode]['answer'];
                } else {
                    $fullAnswer = null;
                }
                break;

            case Question::QT_L_LIST:   //DROPDOWN LIST
            case Question::QT_EXCLAMATION_LIST_DROPDOWN:
                if (mb_substr($fieldName, -5, 5) == 'other') {
                    $fullAnswer = $answerCode;
                } else {
                    if ($answerCode == '-oth-') {
                        $fullAnswer = $translator->translate('Other', $sLanguageCode);
                    } else {
                        $fullAnswer = $answer;
                    }
                }
                break;

            case Question::QT_O_LIST_WITH_COMMENT:   //DROPDOWN LIST WITH COMMENT
                if (isset($answer)) {
                    //This is one of the dropdown list options.
                    $fullAnswer = $answer;
                } else {
                    //This is a comment.
                    $fullAnswer = $answerCode;
                }
                break;

            case Question::QT_Y_YES_NO_RADIO:   //YES/NO
                switch ($answerCode) {
                    case 'Y':
                        $fullAnswer = $translator->translate('Yes', $sLanguageCode);
                        break;

                    case 'N':
                        $fullAnswer = $translator->translate('No', $sLanguageCode);
                        break;

                    default:
                        $fullAnswer = $translator->translate('N/A', $sLanguageCode);
                }
                break;

            case Question::QT_G_GENDER_DROPDOWN:
                switch ($answerCode) {
                    case 'M':
                        $fullAnswer = $translator->translate('Male', $sLanguageCode);
                        break;

                    case 'F':
                        $fullAnswer = $translator->translate('Female', $sLanguageCode);
                        break;

                    default:
                        $fullAnswer = $translator->translate('N/A', $sLanguageCode);
                }
                break;

            case Question::QT_M_MULTIPLE_CHOICE:   //MULTIOPTION
            case Question::QT_P_MULTIPLE_CHOICE_WITH_COMMENTS:
                if (mb_substr($fieldName, -5, 5) == 'other' || mb_substr($fieldName, -7, 7) == 'comment') {
                    //echo "\n -- Branch 1 --";
                    $fullAnswer = $answerCode;
                } else {
                    if ($answerCode == 'Y') {
                        $fullAnswer = $translator->translate('Yes', $sLanguageCode);
                    } elseif ($answerCode == 'N' || $answerCode === '') {
// Strict check for empty string to find null values
                        $fullAnswer = $translator->translate('No', $sLanguageCode);
                    } else {
                        $fullAnswer = $translator->translate('N/A', $sLanguageCode);
                    }
                }
                break;

            case Question::QT_C_ARRAY_YES_UNCERTAIN_NO:
                switch ($answerCode) {
                    case 'Y':
                        $fullAnswer = $translator->translate('Yes', $sLanguageCode);
                        break;

                    case 'N':
                        $fullAnswer = $translator->translate('No', $sLanguageCode);
                        break;

                    case 'U':
                        $fullAnswer = $translator->translate('Uncertain', $sLanguageCode);
                        break;
                }
                break;

            case Question::QT_E_ARRAY_OF_INC_SAME_DEC_QUESTIONS:
                switch ($answerCode) {
                    case 'I':
                        $fullAnswer = $translator->translate('Increase', $sLanguageCode);
                        break;

                    case 'S':
                        $fullAnswer = $translator->translate('Same', $sLanguageCode);
                        break;

                    case 'D':
                        $fullAnswer = $translator->translate('Decrease', $sLanguageCode);
                        break;
                }
                break;

            case Question::QT_F_ARRAY_FLEXIBLE_ROW:
            case Question::QT_H_ARRAY_FLEXIBLE_COLUMN:
                $answers = $this->getAnswers($questionId, 0);
                $fullAnswer = (isset($answers[$answerCode])) ? $answers[$answerCode] : "";
                break;

            default:
                $fullAnswer .= $answerCode;
        }

        return $fullAnswer;
    }

    /**
     * Returns the short answer for the question.
     *
     * @param string $sFieldName
     * @param string $sValue
     * @return string
     */
    public function getShortAnswer($sFieldName, $sValue)
    {
        $aQuestion = $this->fieldMap[$sFieldName];
        $sFieldType = $aQuestion['type'];

        switch ($sFieldType) {
            case Question::QT_K_MULTIPLE_NUMERICAL_QUESTION:
            case Question::QT_N_NUMERICAL:
                if (trim($sValue) != '') {
                    if (strpos($sValue, ".") !== false) {
                        $sValue = rtrim(rtrim($sValue, "0"), ".");
                    }
                }
                break;
        }

        return $sValue;
    }

    /**
     * Returns an array of possible answers to the question.  If $scaleId is
     * specified then only answers that match the $scaleId value will be
     * returned. An empty array may be returned by this function if answers
     * are found that match the questionId.
     *
     * @param int $questionId
     * @param int $scaleId
     * @return array[string]array[string]mixed (or false)
     */
    public function getAnswers($questionId, $scaleId = '0')
    {
        if (isset($this->answers[$questionId]) && isset($this->answers[$questionId][$scaleId])) {
            return $this->answers[$questionId][$scaleId];
        }
        return array();
    }
}
