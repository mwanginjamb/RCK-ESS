<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 5:23 AM
 */

namespace frontend\models;


use yii\base\Model;
use Yii;

class Employee extends Model
{
    public $No;
    public $First_Name;
    public $Middle_Name;
    public $Last_Name;
    public $Full_Name;
    public $Job_Title;
    public $Job_Description;
    public $Initials;
    public $Search_Name;
    public $Gender;
    public $Phone_No_2;
    public $Company_E_Mail;
    public $Last_Date_Modified;
    public $Privacy_Blocked;
    public $Global_Dimension_1_Code;
    public $Global_Dimension_1_Name;
    public $Global_Dimension_2_Code;
    public $Max_Imprest_Amount;
    public $Suspend_Leave_Application;
    public $Maximum_Applicable_Trainings;
    public $Balance;
    public $Address;
    public $Address_2;
    public $Post_Code;
    public $City;
    public $Country_Region_Code;
    public $ShowMap;
    public $Mobile_Phone_No;
    public $Pager;
    public $Extension;
    public $E_Mail;
    public $Alt_Address_Code;
    public $Alt_Address_Start_Date;
    public $Alt_Address_End_Date;
    public $Birth_Date;
    public $Period_To_Retirement;
    public $Employment_Date;
    public $Date_of_Leaving;
    public $Service_Period;
    public $National_ID;
    public $Age;
    public $Physical_Address;
    public $Grade;
    public $Pointer;
    public $NHIF_Number;
    public $NSSF_Number;
    public $KRA_Number;
    public $Marital_Status;
    public $Manager;
    public $Is_Long_Term;
    public $Supervisor_Code;
    public $Payment_Methods;
    public $Nature_Of_Employment;
    public $Disabled;
    public $Status;
    public $Employee_Category;
    public $Procurement_Comm_Member;
    public $End_of_Contract_Date;
    public $Notice_Period;
    public $Inactive_Date;
    public $Cause_of_Inactivity_Code;
    public $Termination_Date;
    public $Grounds_for_Term_Code;
    public $Emplymt_Contract_Code;
    public $Statistics_Group_Code;
    public $Resource_No;
    public $Salespers_Purch_Code;
    public $Manager_No;
    public $Probation_Status;
    public $Probation_Period_Extended;
    public $End_of_Probation_Period;
    public $Probabtion_Extended_By;
    public $New_Probation_Period_End_Date;
    public $Reasons_For_Extension;
    public $ProfileID;
    public $User_ID;
    public $Employee_Posting_Group;
    public $EFT_Format;
    public $Application_Method;
    public $Bank_Code;
    public $Bank_Branch_Code;
    public $Bank_Name;
    public $Bank_Branch_Name;
    public $Bank_Account_No;
    public $IBAN;
    public $SWIFT_Code;
    public $Key;
    public $County_of_Origin;
    public $Sub_County;
    public $Location;
    public $Sub_Location;
    public $Village;
    public $Passport_Number;
    public $Ethnic_Origin;
    public $Religion;
    public $Driving_License;
    public $Health_Conditions;
    public $Phone_No;
    public $Alternative_Phone_No;
    public $Contract_Start_Date;
    public $Contract_End_Date;
    public $Date_of_joining_Medical_Scheme;
    public $Global_Dimension_3_Code;
    public $Global_Dimension_4_Code;
    public $Global_Dimension_5_Code;
    public $Line_Manager_Name;
    public $Overview_Manager_Name;
    public $Grant_Approver_Name;
    public $Disability_Id;
    public $Covered_Medically;
    public $Currency;
    public $Bank_Branch_No;
    public $Branch_Name;
    public $Payroll_Grade;
    public $Probation_Period;
    public $Global_Dimension_6_Code;
    public $Grant_Approver;
    public $_x002B_;
    public $Type_of_Employee;
    public $Allien_Number;
    public $Starting_Date;
    public $Division_Name;
    public $Department_Name;
    public $Section_Name;
    public $Unit_Name;

    public function rules()
    {
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {

        return [
            'Global_Dimension_1_Code' => 'Sub Office Code',
            'Global_Dimension_1_Name' => 'Program Name',
            'Global_Dimension_2_Code' => 'Program Code',
            'Global_Dimension_3_Code' => 'Section Code',
            'Global_Dimension_4_Code' => 'Unit',
            'Global_Dimension_5_Code' => 'Location',
            'Job_Description' => 'Job Title',
            'Job_Title' => 'Job Code',
            'Mobile_Phone_No' => 'Private Phone Number'
        ];
    }

    

}