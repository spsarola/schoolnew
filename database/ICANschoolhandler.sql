-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2014 at 02:12 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `newschool`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `AccountId` int(11) NOT NULL auto_increment,
  `AccountStatus` varchar(10) NOT NULL,
  `ManagedBy` varchar(100) NOT NULL,
  `AccountName` varchar(100) NOT NULL,
  `BankAccountName` varchar(100) NOT NULL,
  `AccountType` int(11) NOT NULL,
  `BankName` varchar(100) NOT NULL,
  `BranchName` varchar(100) NOT NULL,
  `IFSCCode` varchar(10) NOT NULL,
  `OpeningBalance` decimal(10,2) NOT NULL,
  `AccountBalance` decimal(10,2) NOT NULL,
  `AccountDate` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  PRIMARY KEY  (`AccountId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `accounts`
--


-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

CREATE TABLE `admission` (
  `AdmissionId` int(11) NOT NULL auto_increment,
  `AdmissionNo` varchar(100) NOT NULL,
  `RegistrationId` int(11) NOT NULL,
  `Remarks` text NOT NULL,
  `DOA` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  PRIMARY KEY  (`AdmissionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admission`
--


-- --------------------------------------------------------

--
-- Table structure for table `backuprestore`
--

CREATE TABLE `backuprestore` (
  `BackUpRestoreId` int(11) NOT NULL auto_increment,
  `BackUpRestoreType` varchar(20) NOT NULL,
  `BackUpRestoreDate` varchar(20) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Result` text NOT NULL,
  PRIMARY KEY  (`BackUpRestoreId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `backuprestore`
--


-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `BookId` int(11) NOT NULL auto_increment,
  `BookStatus` varchar(10) NOT NULL,
  `BookName` varchar(100) NOT NULL,
  `AuthorName` varchar(100) NOT NULL,
  `Publisher` varchar(100) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `Purpose` int(11) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `DOE` varchar(100) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`BookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `book`
--


-- --------------------------------------------------------

--
-- Table structure for table `bookissue`
--

CREATE TABLE `bookissue` (
  `BookIssueId` int(11) NOT NULL auto_increment,
  `IRTo` varchar(10) NOT NULL,
  `IRToDetail` int(11) NOT NULL,
  `Books` text NOT NULL,
  `DOI` varchar(10) NOT NULL,
  `BookReturn` text NOT NULL,
  `BookIssueStatus` varchar(10) NOT NULL,
  `Remarks` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `DOD` varchar(10) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`BookIssueId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bookissue`
--


-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `CalendarId` int(11) NOT NULL auto_increment,
  `CalendarStatus` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `StartTime` varchar(20) NOT NULL,
  `EndTime` varchar(20) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Color` varchar(7) NOT NULL,
  `Date` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`CalendarId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `calendar`
--


-- --------------------------------------------------------

--
-- Table structure for table `calling`
--

CREATE TABLE `calling` (
  `CallId` int(11) NOT NULL auto_increment,
  `CallStatus` varchar(10) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `Landline` varchar(12) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `NoOfChild` int(11) NOT NULL,
  `CallResponse` int(11) NOT NULL,
  `ResponseDetail` text NOT NULL,
  `FollowUpDate` varchar(20) NOT NULL,
  `Remarks` text NOT NULL,
  `Address` text NOT NULL,
  `DOC` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  PRIMARY KEY  (`CallId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `calling`
--

CREATE TABLE `circular` (
  `CircularId` int(11) NOT NULL auto_increment,
  `Title` varchar(10000) NOT NULL,
  `Circular` text NOT NULL,
  `DateReleased` varchar(10) NOT NULL,
  `CircularStatus` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  PRIMARY KEY  (`CircularId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `ClassId` int(11) NOT NULL auto_increment,
  `Session` varchar(10) NOT NULL,
  `ClassName` varchar(100) NOT NULL,
  `ClassStatus` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`ClassId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `class`
--


-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `ComplaintId` int(11) NOT NULL auto_increment,
  `ComplaintStatus` varchar(10) NOT NULL,
  `ComplaintType` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `Description` text NOT NULL,
  `Action` text NOT NULL,
  `DOC` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  `DOD` varchar(10) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`ComplaintId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `complaint`
--


-- --------------------------------------------------------

--
-- Table structure for table `drregister`
--

CREATE TABLE `drregister` (
  `Id` int(11) NOT NULL auto_increment,
  `DRStatus` varchar(10) NOT NULL,
  `DRType` varchar(100) NOT NULL,
  `Reference` text NOT NULL,
  `Title` text NOT NULL,
  `AddressFrom` text NOT NULL,
  `AddressTo` text NOT NULL,
  `Date` varchar(10) NOT NULL,
  `Remarks` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOD` varchar(10) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `drregister`
--


-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `EnquiryId` int(11) NOT NULL auto_increment,
  `EnquiryStatus` varchar(10) NOT NULL,
  `EnquiryType` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `NoOfChild` int(11) NOT NULL,
  `EnquiryResponse` int(11) NOT NULL,
  `AlternateMobile` varchar(10) NOT NULL,
  `Address` text NOT NULL,
  `EnquiryDate` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `ResponseDetail` text NOT NULL,
  `Remarks` text NOT NULL,
  `Reference` int(11) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  PRIMARY KEY  (`EnquiryId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `enquiry`
--


-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `ExamId` int(11) NOT NULL auto_increment,
  `ExamStatus` varchar(10) NOT NULL,
  `Session` varchar(10) NOT NULL,
  `SectionId` int(11) NOT NULL,
  `ExamName` varchar(100) NOT NULL,
  `Weightage` decimal(10,2) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`ExamId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `exam`
--


-- --------------------------------------------------------

--
-- Table structure for table `examdetail`
--

CREATE TABLE `examdetail` (
  `ExamDetailId` int(11) NOT NULL auto_increment,
  `ExamDetailStatus` varchar(10) NOT NULL,
  `Locked` int(11) NOT NULL,
  `ExamId` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `ExamActivityName` varchar(500) NOT NULL,
  `ExamActivityType` int(11) NOT NULL,
  `MaximumMarks` decimal(10,0) NOT NULL,
  `Marks` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`ExamDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `examdetail`
--


-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `ExpenseId` int(11) NOT NULL auto_increment,
  `Username` varchar(100) NOT NULL,
  `ExpenseStatus` varchar(10) NOT NULL,
  `ExpenseAccountType` varchar(20) NOT NULL,
  `SupplierId` varchar(10) NOT NULL,
  `StaffId` varchar(10) NOT NULL,
  `SalaryMonthYear` varchar(20) NOT NULL,
  `SalaryPaymentType` varchar(10) NOT NULL,
  `ExpenseAmount` decimal(10,2) NOT NULL,
  `AmountPaid` decimal(10,2) NOT NULL,
  `ExpenseRemarks` text NOT NULL,
  `ExpenseDate` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`ExpenseId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `expense`
--


-- --------------------------------------------------------

--
-- Table structure for table `fee`
--

CREATE TABLE `fee` (
  `FeeId` int(11) NOT NULL auto_increment,
  `FeeStatus` varchar(10) NOT NULL,
  `Session` varchar(10) NOT NULL,
  `SectionId` int(11) NOT NULL,
  `FeeType` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `Distance` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`FeeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `fee`
--


-- --------------------------------------------------------

--
-- Table structure for table `feepayment`
--

CREATE TABLE `feepayment` (
  `FeePaymentId` int(11) NOT NULL auto_increment,
  `Token` varchar(100) NOT NULL,
  `FeeType` int(11) NOT NULL,
  `Amount` decimal(10,0) NOT NULL,
  `FeePaymentStatus` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  PRIMARY KEY  (`FeePaymentId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `feepayment`
--


-- --------------------------------------------------------

--
-- Table structure for table `followup`
--

CREATE TABLE `followup` (
  `FollowUpId` int(11) NOT NULL auto_increment,
  `FollowUpStatus` varchar(10) NOT NULL,
  `FollowUpType` varchar(10) NOT NULL,
  `FollowUpUniqueId` int(11) NOT NULL,
  `ResponseDetail` text NOT NULL,
  `Remarks` text NOT NULL,
  `NextFollowUpDate` varchar(20) NOT NULL,
  `DOF` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  PRIMARY KEY  (`FollowUpId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `followup`
--


-- --------------------------------------------------------

--
-- Table structure for table `generalsetting`
--

CREATE TABLE `generalsetting` (
  `Id` int(11) NOT NULL auto_increment,
  `CurrentSession` varchar(10) NOT NULL,
  `BackUpPath` varchar(100) NOT NULL,
  `SchoolStartDate` varchar(20) NOT NULL,
  `SchoolName` varchar(500) NOT NULL,
  `SchoolAddress` text NOT NULL,
  `City` varchar(100) NOT NULL,
  `District` varchar(100) NOT NULL,
  `PIN` varchar(6) NOT NULL,
  `State` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `AlternateMobile` varchar(10) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Landline` varchar(12) NOT NULL,
  `Fax` varchar(12) NOT NULL,
  `DateOfEstablishment` varchar(100) NOT NULL,
  `Board` varchar(100) NOT NULL,
  `AffiliatedBy` varchar(100) NOT NULL,
  `RegistrationNo` varchar(100) NOT NULL,
  `AffiliationNo` varchar(100) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `DOL` varchar(20) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `generalsetting`
--


-- --------------------------------------------------------

--
-- Table structure for table `header`
--

CREATE TABLE `header` (
  `HeaderId` int(11) NOT NULL auto_increment,
  `HRType` varchar(10) NOT NULL,
  `HeaderTitle` varchar(1000) NOT NULL,
  `HeaderContent` text NOT NULL,
  `HeaderDefault` varchar(3) NOT NULL,
  PRIMARY KEY  (`HeaderId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `header`
--


-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE `house` (
  `HouseId` int(11) NOT NULL auto_increment,
  `HouseName` varchar(100) NOT NULL,
  `HouseStatus` varchar(10) NOT NULL,
  `Session` varchar(10) NOT NULL,
  `Students` text NOT NULL,
  `HouseIncharge` text NOT NULL,
  `HouseCaptain` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  `DOD` varchar(10) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`HouseId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `house`
--


-- --------------------------------------------------------

--
-- Table structure for table `issue`
--

CREATE TABLE `issue` (
  `IssueId` int(11) NOT NULL auto_increment,
  `IssueStatus` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `AdmissionId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `Session` varchar(12) NOT NULL,
  `MaterialType` varchar(100) NOT NULL,
  `Material` text NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `Paid` decimal(10,2) NOT NULL,
  `PaidFrom` varchar(20) NOT NULL,
  `Remarks` text NOT NULL,
  `DOI` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  PRIMARY KEY  (`IssueId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `issue`
--

-- --------------------------------------------------------

--
-- Table structure for table `listbook`
--

CREATE TABLE `listbook` (
  `ListBookId` int(11) NOT NULL auto_increment,
  `Token` varchar(100) NOT NULL,
  `BookId` int(11) NOT NULL,
  `AccessionNo` varchar(100) NOT NULL,
  `IRStatus` varchar(10) NOT NULL,
  `ListBookStatus` varchar(10) NOT NULL,
  `ListBookCondition` int(11) NOT NULL,
  PRIMARY KEY  (`ListBookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `listbook`
--


-- --------------------------------------------------------

--
-- Table structure for table `listbookconfirm`
--

CREATE TABLE `listbookconfirm` (
  `ListBookConfirmId` int(11) NOT NULL auto_increment,
  `Token` varchar(100) NOT NULL,
  `DOA` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `ListBookConfirmStatus` varchar(10) NOT NULL,
  `Remarks` text NOT NULL,
  PRIMARY KEY  (`ListBookConfirmId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `listbookconfirm`
--


-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `LocationId` int(11) NOT NULL auto_increment,
  `LocationName` varchar(100) NOT NULL,
  `CalledAs` varchar(100) NOT NULL,
  `LocationStatus` varchar(10) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`LocationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `location`
--


-- --------------------------------------------------------

--
-- Table structure for table `masterentry`
--

CREATE TABLE `masterentry` (
  `MasterEntryId` int(11) NOT NULL auto_increment,
  `MasterEntryStatus` varchar(10) NOT NULL,
  `MasterEntryName` varchar(100) NOT NULL,
  `MasterEntryValue` varchar(100) NOT NULL,
  PRIMARY KEY  (`MasterEntryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `masterentry`
--

INSERT INTO `masterentry` (`MasterEntryId`, `MasterEntryStatus`, `MasterEntryName`, `MasterEntryValue`) VALUES
(1, 'Active', 'AccountType', 'Cash'),
(2, 'Active', 'AccountType', 'Bank'),
(3, 'Active', 'AssignTo', 'Student'),
(4, 'Active', 'AssignTo', 'Staff'),
(5, 'Active', 'BookPurpose', 'Issue'),
(6, 'Active', 'BookPurpose', 'Reference'),
(7, 'Active', 'Gender', 'Male'),
(8, 'Active', 'Gender', 'Female'),
(9, 'Active', 'OtherAssignTo', 'Missing'),
(10, 'Active', 'OtherAssignTo', 'Damage'),
(11, 'Active', 'Resolution', '800x600'),
(12, 'Active', 'SalaryHeadType', 'Earning'),
(13, 'Active', 'SalaryHeadType', 'Deduction'),
(14, 'Active', 'HeaderFooter', 'Header'),
(15, 'Active', 'HeaderFooter', 'Footer'),
(16, 'Active', 'RouteTo', 'To Home'),
(17, 'Active', 'RouteTo', 'To School'),
(18, 'Active', 'AssignTo', 'Location'),
(19, 'Active', 'AssignTo', 'Other'),
(20, 'Active', 'GradingPoint', '1'),
(21, 'Active', 'GradingPoint', '2'),
(22, 'Active', 'GradingPoint', '3'),
(23, 'Active', 'GradingPoint', '4'),
(24, 'Active', 'GradingPoint', '5'),
(25, 'Active', 'GradingPoint', '6'),
(26, 'Active', 'GradingPoint', '7'),
(27, 'Active', 'GradingPoint', '8'),
(28, 'Active', 'GradingPoint', '9');

-- --------------------------------------------------------

--
-- Table structure for table `masterentrycategory`
--

CREATE TABLE `masterentrycategory` (
  `MasterEntryCategoryId` int(11) NOT NULL auto_increment,
  `MasterEntryCategoryName` varchar(100) NOT NULL,
  `MasterEntryCategoryValue` varchar(100) NOT NULL,
  `Permission` varchar(10) NOT NULL,
  PRIMARY KEY  (`MasterEntryCategoryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `masterentrycategory`
--

INSERT INTO `masterentrycategory` (`MasterEntryCategoryId`, `MasterEntryCategoryName`, `MasterEntryCategoryValue`, `Permission`) VALUES
(1, 'Gender', 'Gender', 'Webmaster'),
(2, 'Account Type', 'AccountType', 'Webmaster'),
(3, 'User Type', 'UserType', ''),
(4, 'Fee Type', 'FeeType', ''),
(5, 'Distance', 'Distance', ''),
(7, 'Exam Activity Type', 'ExamActivityType', ''),
(8, 'Staff Position', 'StaffPosition', ''),
(9, 'Route Stoppage', 'RouteStoppage', ''),
(10, 'Enquiry Response', 'EnquiryResponse', ''),
(11, 'Reference', 'Reference', ''),
(12, 'Enquiry Type', 'EnquiryType', ''),
(13, 'Caste', 'Caste', ''),
(14, 'Category', 'Category', ''),
(15, 'Blood Group', 'BloodGroup', ''),
(16, 'Students Documents', 'StudentsDocuments', ''),
(17, 'Resolution', 'Resolution', 'Webmaster'),
(18, 'Complaint Type', 'ComplaintType', ''),
(19, 'Guest Visiting Purpose', 'GuestVistingPurpose', ''),
(20, 'Salary Head Type', 'SalaryHeadType', 'Webmaster'),
(21, 'Income Account', 'IncomeAccount', ''),
(22, 'Expense Account', 'ExpenseAccount', ''),
(23, 'Print Category', 'PrintCategory', 'Webmaster'),
(24, 'Book Purpose', 'BookPurpose', 'Webmaster'),
(25, 'Co Scholastic Part', 'CoScholasticPart', ''),
(26, 'Grading Point', 'GradingPoint', 'Webmaster'),
(27, 'Staff Documents', 'StaffDocuments', ''),
(28, 'Stock Type', 'StockType', ''),
(29, 'Unit', 'Unit', ''),
(30, 'Assign To', 'AssignTo', 'Webmaster'),
(31, 'Other Assign To', 'OtherAssignTo', 'Webmaster'),
(32, 'Header Footer', 'HeaderFooter', 'Webmaster'),
(33, 'Route To', 'RouteTo', 'Webmaster'),
(34, 'Termination Reason', 'TerminationReason', '');

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `NoteId` int(11) NOT NULL auto_increment,
  `Username` varchar(100) NOT NULL,
  `UniqueId` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `Date` varchar(20) NOT NULL,
  PRIMARY KEY  (`NoteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `note`
--


-- --------------------------------------------------------

--
-- Table structure for table `ocalling`
--

CREATE TABLE `ocalling` (
  `OCallId` int(11) NOT NULL auto_increment,
  `CallStatus` varchar(10) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `Landline` varchar(12) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `FollowUpDate` varchar(20) NOT NULL,
  `CallDuration` varchar(100) NOT NULL,
  `Remarks` text NOT NULL,
  `DOC` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  PRIMARY KEY  (`OCallId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ocalling`
--


-- --------------------------------------------------------

--
-- Table structure for table `pagename`
--

CREATE TABLE `pagename` (
  `PageNameId` int(11) NOT NULL auto_increment,
  `PageName` varchar(100) NOT NULL,
  PRIMARY KEY  (`PageNameId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pagename`
--

INSERT INTO `pagename` (`PageNameId`, `PageName`) VALUES
(1, 'MasterEntry'),
(2, 'ManageUser'),
(3, 'ManageAccounts'),
(4, 'ManageClass'),
(5, 'ManageSubject'),
(6, 'ManageExam'),
(7, 'ManageSCArea'),
(8, 'Payment');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `PermissionId` int(11) NOT NULL auto_increment,
  `UserType` int(11) NOT NULL,
  `PermissionString` text NOT NULL,
  PRIMARY KEY  (`PermissionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `permission`
--


-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `PhotoId` int(11) NOT NULL auto_increment,
  `Title` varchar(100) NOT NULL,
  `Path` varchar(100) NOT NULL,
  `Document` int(11) NOT NULL,
  `Detail` varchar(100) NOT NULL,
  `UniqueId` int(11) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  PRIMARY KEY  (`PhotoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `photos`
--


-- --------------------------------------------------------

--
-- Table structure for table `printoption`
--

CREATE TABLE `printoption` (
  `PrintOptionId` int(11) NOT NULL auto_increment,
  `PrintCategory` int(11) NOT NULL,
  `Width` decimal(10,0) NOT NULL,
  `HeaderId` varchar(10) NOT NULL,
  `FooterId` varchar(10) NOT NULL,
  `PrintOptionStatus` varchar(10) NOT NULL,
  PRIMARY KEY  (`PrintOptionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `printoption`
--


-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `PurchaseId` int(11) NOT NULL auto_increment,
  `PurchaseStatus` varchar(10) NOT NULL,
  `Token` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `SupplierId` int(11) NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `Paid` decimal(10,2) NOT NULL,
  `DOP` varchar(20) NOT NULL,
  `DOE` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `Remarks` text character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`PurchaseId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `purchase`
--


-- --------------------------------------------------------

--
-- Table structure for table `purchaselist`
--

CREATE TABLE `purchaselist` (
  `PurchaseListId` int(11) NOT NULL auto_increment,
  `Token` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `MaterialType` varchar(100) NOT NULL,
  `UniqueId` int(11) NOT NULL,
  `Quantity` decimal(10,2) NOT NULL,
  `PurchasePrice` decimal(10,2) NOT NULL,
  `OtherInfo` text NOT NULL,
  `Date` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`PurchaseListId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `purchaselist`
--


-- --------------------------------------------------------

--
-- Table structure for table `qualification`
--

CREATE TABLE `qualification` (
  `QualificationId` int(11) NOT NULL auto_increment,
  `Type` varchar(10) NOT NULL,
  `UniqueId` int(11) NOT NULL,
  `BoardUniversity` varchar(200) NOT NULL,
  `Class` varchar(100) NOT NULL,
  `Year` varchar(100) NOT NULL,
  `Marks` varchar(100) NOT NULL,
  `Remarks` text NOT NULL,
  PRIMARY KEY  (`QualificationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `qualification`
--


-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `RegistrationId` int(11) NOT NULL auto_increment,
  `Session` varchar(10) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `StudentName` varchar(100) NOT NULL,
  `FatherName` varchar(100) NOT NULL,
  `FatherMobile` varchar(10) NOT NULL,
  `FatherDateOfBirth` varchar(10) NOT NULL,
  `FatherEmail` varchar(100) NOT NULL,
  `FatherQualification` varchar(100) NOT NULL,
  `FatherOccupation` varchar(100) NOT NULL,
  `FatherDesignation` varchar(100) NOT NULL,
  `FatherOrganization` varchar(100) NOT NULL,
  `MotherName` varchar(100) NOT NULL,
  `MotherMobile` varchar(10) NOT NULL,
  `MotherDateOfBirth` varchar(10) NOT NULL,
  `MotherEmail` varchar(100) NOT NULL,
  `MotherQualification` varchar(100) NOT NULL,
  `MotherOccupation` varchar(100) NOT NULL,
  `MotherDesignation` varchar(100) NOT NULL,
  `MotherOrganization` varchar(100) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `SectionId` int(11) NOT NULL,
  `DOB` varchar(20) NOT NULL,
  `DOR` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `Landline` varchar(12) NOT NULL,
  `AlternateMobile` varchar(10) NOT NULL,
  `PresentAddress` text NOT NULL,
  `PermanentAddress` text NOT NULL,
  `BloodGroup` int(11) NOT NULL,
  `Caste` int(11) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Gender` int(11) NOT NULL,
  `Nationality` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `ParentsPassword` varchar(100) NOT NULL,
  `StudentsPassword` varchar(100) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  `DOD` varchar(10) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  `DateOfTermination` varchar(10) NOT NULL,
  `TerminationReason` varchar(10) NOT NULL,
  `TerminationRemarks` text NOT NULL,
  `DOT` varchar(10) NOT NULL,
  PRIMARY KEY  (`RegistrationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `registration`
--


-- --------------------------------------------------------

--
-- Table structure for table `salaryhead`
--

CREATE TABLE `salaryhead` (
  `SalaryHeadId` int(11) NOT NULL auto_increment,
  `SalaryHeadType` int(11) NOT NULL,
  `SalaryHead` varchar(100) NOT NULL,
  `Code` varchar(100) NOT NULL,
  `DailyBasis` int(1) NOT NULL,
  `SalaryHeadStatus` varchar(10) NOT NULL,
  PRIMARY KEY  (`SalaryHeadId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `salaryhead`
--


-- --------------------------------------------------------

--
-- Table structure for table `salarystructure`
--

CREATE TABLE `salarystructure` (
  `SalaryStructureId` int(11) NOT NULL auto_increment,
  `SalaryStructureName` varchar(100) NOT NULL,
  `FixedSalaryHead` text NOT NULL,
  `SalaryStructureStatus` varchar(10) NOT NULL,
  PRIMARY KEY  (`SalaryStructureId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `salarystructure`
--


-- --------------------------------------------------------

--
-- Table structure for table `salarystructuredetail`
--

CREATE TABLE `salarystructuredetail` (
  `SalaryStructureDetailId` int(11) NOT NULL auto_increment,
  `SalaryStructureId` int(11) NOT NULL,
  `SalaryHeadId` int(11) NOT NULL,
  `Expression` text NOT NULL,
  PRIMARY KEY  (`SalaryStructureDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `salarystructuredetail`
--


-- --------------------------------------------------------

--
-- Table structure for table `scarea`
--

CREATE TABLE `scarea` (
  `SCAreaId` int(11) NOT NULL auto_increment,
  `Session` varchar(10) NOT NULL,
  `SCPartId` int(11) NOT NULL,
  `GradingPoint` int(11) NOT NULL,
  `SCAreaName` varchar(100) NOT NULL,
  `SCAreaClass` text NOT NULL,
  `SCAreaStatus` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`SCAreaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `scarea`
--


-- --------------------------------------------------------

--
-- Table structure for table `scexamdetail`
--

CREATE TABLE `scexamdetail` (
  `SCExamDetailId` int(11) NOT NULL auto_increment,
  `ExamId` int(11) NOT NULL,
  `SCAreaId` int(11) NOT NULL,
  `Marks` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`SCExamDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `scexamdetail`
--


-- --------------------------------------------------------

--
-- Table structure for table `schoolmaterial`
--

CREATE TABLE `schoolmaterial` (
  `SchoolMaterialId` int(11) NOT NULL auto_increment,
  `SchoolMaterialStatus` varchar(10) NOT NULL,
  `Session` varchar(10) NOT NULL,
  `SchoolMaterialType` varchar(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `BranchPrice` decimal(10,2) NOT NULL,
  `SellingPrice` decimal(10,2) NOT NULL,
  `Date` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`SchoolMaterialId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `schoolmaterial`
--


-- --------------------------------------------------------

--
-- Table structure for table `scindicator`
--

CREATE TABLE `scindicator` (
  `SCIndicatorId` int(11) NOT NULL auto_increment,
  `SCAreaId` int(11) NOT NULL,
  `SCIndicatorName` varchar(100) NOT NULL,
  `SCIndicatorStatus` varchar(10) NOT NULL,
  PRIMARY KEY  (`SCIndicatorId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `scindicator`
--


-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `SectionId` int(11) NOT NULL auto_increment,
  `ClassId` int(11) NOT NULL,
  `SectionName` varchar(100) NOT NULL,
  `SectionStatus` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`SectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `section`
--


-- --------------------------------------------------------

--
-- Table structure for table `sibling`
--

CREATE TABLE `sibling` (
  `SiblingId` int(11) NOT NULL auto_increment,
  `RegistrationId` int(11) NOT NULL,
  `SName` varchar(100) NOT NULL,
  `SDOB` varchar(10) NOT NULL,
  `SClass` varchar(100) NOT NULL,
  `SSchool` text NOT NULL,
  `SRemarks` text NOT NULL,
  PRIMARY KEY  (`SiblingId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sibling`
--


-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffId` int(11) NOT NULL auto_increment,
  `StaffStatus` varchar(10) NOT NULL,
  `StaffPosition` int(11) NOT NULL,
  `StaffName` varchar(100) NOT NULL,
  `StaffMobile` varchar(10) NOT NULL,
  `StaffEmail` varchar(100) NOT NULL,
  `StaffAlternateMobile` varchar(10) NOT NULL,
  `StaffFName` varchar(10) NOT NULL,
  `StaffMName` varchar(10) NOT NULL,
  `StaffDOJ` varchar(20) NOT NULL,
  `StaffDOB` varchar(20) NOT NULL,
  `StaffPresentAddress` text NOT NULL,
  `StaffPermanentAddress` text NOT NULL,
  `StaffRemarks` text NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`StaffId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `staff`
--


-- --------------------------------------------------------

--
-- Table structure for table `staffattendance`
--

CREATE TABLE `staffattendance` (
  `StaffAttendanceId` int(11) NOT NULL auto_increment,
  `Date` varchar(20) NOT NULL,
  `Attendance` text NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`StaffAttendanceId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `staffattendance`
--


-- --------------------------------------------------------

--
-- Table structure for table `staffsalary`
--

CREATE TABLE `staffsalary` (
  `StaffSalaryId` int(11) NOT NULL auto_increment,
  `StaffSalaryStatus` varchar(10) NOT NULL,
  `StaffId` int(11) NOT NULL,
  `SalaryStructureId` int(11) NOT NULL,
  `FixedSalary` text NOT NULL,
  `StaffPaidLeave` int(11) NOT NULL,
  `EffectiveFrom` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `Remarks` text NOT NULL,
  PRIMARY KEY  (`StaffSalaryId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `staffsalary`
--


-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `StockId` int(11) NOT NULL auto_increment,
  `StockStatus` varchar(10) NOT NULL,
  `StockType` int(11) NOT NULL,
  `StockName` varchar(500) NOT NULL,
  `Unit` int(11) NOT NULL,
  `OpeningStock` decimal(10,2) NOT NULL,
  `CurrentStock` decimal(10,2) NOT NULL,
  `Date` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  PRIMARY KEY  (`StockId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `stock`
--


-- --------------------------------------------------------

--
-- Table structure for table `stockassign`
--

CREATE TABLE `stockassign` (
  `StockAssignId` int(11) NOT NULL auto_increment,
  `Username` varchar(100) NOT NULL,
  `StockAssignStatus` varchar(10) NOT NULL,
  `StockId` int(11) NOT NULL,
  `Quantity` decimal(10,2) NOT NULL,
  `Returning` decimal(10,2) NOT NULL,
  `AssignTo` varchar(100) NOT NULL,
  `AssignToDetail` varchar(100) NOT NULL,
  `DOT` varchar(20) NOT NULL,
  `Remarks` text NOT NULL,
  `DOE` varchar(20) NOT NULL,
  PRIMARY KEY  (`StockAssignId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `stockassign`
--


-- --------------------------------------------------------

--
-- Table structure for table `studentattendance`
--

CREATE TABLE `studentattendance` (
  `StudentAttendanceId` int(11) NOT NULL auto_increment,
  `Date` varchar(10) NOT NULL,
  `Attendance` text NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`StudentAttendanceId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `studentattendance`
--


-- --------------------------------------------------------

--
-- Table structure for table `studentfee`
--

CREATE TABLE `studentfee` (
  `StudentFeeId` int(11) NOT NULL auto_increment,
  `StudentFeeStatus` varchar(10) NOT NULL,
  `AdmissionNo` varchar(10) NOT NULL,
  `AdmissionId` int(11) NOT NULL,
  `Session` varchar(10) NOT NULL,
  `SectionId` int(11) NOT NULL,
  `FeeStructure` text NOT NULL,
  `Distance` varchar(10) NOT NULL,
  `Remarks` text NOT NULL,
  `Date` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `Username` varchar(100) NOT NULL,
  PRIMARY KEY  (`StudentFeeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `studentfee`
--


-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SubjectId` int(11) NOT NULL auto_increment,
  `Session` varchar(10) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `SubjectAbb` varchar(100) NOT NULL,
  `Class` text NOT NULL,
  `SubjectStatus` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`SubjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `subject`
--


-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierId` int(11) NOT NULL auto_increment,
  `SupplierStatus` varchar(10) NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `SupplierMobile` varchar(10) NOT NULL,
  `SupplierAddress` text NOT NULL,
  `SupplierRemarks` text NOT NULL,
  `Date` varchar(20) NOT NULL,
  `DLU` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`SupplierId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `supplier`
--


-- --------------------------------------------------------

--
-- Table structure for table `tablename`
--

CREATE TABLE `tablename` (
  `TableName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tablename`
--

INSERT INTO `tablename` (`TableName`) VALUES
('accounts'),
('admission'),
('backuprestore'),
('book'),
('bookissue'),
('calendar'),
('calling'),
('class'),
('complaint'),
('drregister'),
('enquiry'),
('exam'),
('examdetail'),
('expense'),
('fee'),
('feepayment'),
('followup'),
('generalsetting'),
('header'),
('house'),
('issue'),
('listbook'),
('listbookconfirm'),
('location'),
('masterentry'),
('masterentrycategory'),
('pagename'),
('permission'),
('photos'),
('printoption'),
('purchase'),
('purchaselist'),
('qualification'),
('registration'),
('salaryhead'),
('salarystructure'),
('salarystructuredetail'),
('scarea'),
('scexamdetail'),
('schoolmaterial'),
('scindicator'),
('section'),
('sibling'),
('staff'),
('staffattendance'),
('staffsalary'),
('stock'),
('stockassign'),
('studentattendance'),
('studentfee'),
('subject'),
('supplier'),
('transaction'),
('user'),
('vehicle'),
('vehiclefuel'),
('vehiclereading'),
('vehicleroute'),
('vehicleroutedetail'),
('visitorbook');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `TransactionId` int(11) NOT NULL auto_increment,
  `Username` varchar(100) NOT NULL,
  `Token` varchar(100) NOT NULL,
  `TransactionSession` varchar(10) NOT NULL,
  `TransactionAmount` varchar(100) NOT NULL,
  `TransactionType` varchar(100) NOT NULL,
  `TransactionFrom` varchar(100) NOT NULL,
  `TransactionHead` varchar(100) NOT NULL,
  `TransactionSubHead` varchar(10) NOT NULL,
  `TransactionHeadId` varchar(100) NOT NULL,
  `TransactionRemarks` text NOT NULL,
  `TransactionDate` varchar(20) NOT NULL,
  `TransactionDOE` varchar(20) NOT NULL,
  `TransactionDLU` varchar(20) NOT NULL,
  `TransactionDOD` varchar(20) NOT NULL,
  `TransactionIP` varchar(50) NOT NULL,
  `TransactionStatus` varchar(10) NOT NULL,
  `TransactionDODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`TransactionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `transaction`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL auto_increment,
  `StaffId` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `UserType` int(11) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserId`, `StaffId`, `Username`, `Password`, `UserType`, `DOE`, `DOL`, `DOLUsername`) VALUES
(1, '', 'webmaster', '50a9c7dbf0fa09e8969978317dca12e8', 0, '', '', ''),
(2, '', 'masteruser', 'e10adc3949ba59abbe56e057f20f883e', 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `VehicleId` int(11) NOT NULL auto_increment,
  `VehicleStatus` varchar(10) NOT NULL,
  `VehicleNumber` varchar(100) NOT NULL,
  `VehicleName` varchar(100) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`VehicleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vehicle`
--


-- --------------------------------------------------------

--
-- Table structure for table `vehiclefuel`
--

CREATE TABLE `vehiclefuel` (
  `FuelId` int(11) NOT NULL auto_increment,
  `FuelStatus` varchar(10) NOT NULL,
  `VehicleId` int(11) NOT NULL,
  `ReceiptNo` varchar(100) NOT NULL,
  `Quantity` decimal(10,2) NOT NULL,
  `Rate` decimal(10,2) NOT NULL,
  `DOF` varchar(20) NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DOL` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  `Remarks` text NOT NULL,
  PRIMARY KEY  (`FuelId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vehiclefuel`
--


-- --------------------------------------------------------

--
-- Table structure for table `vehiclereading`
--

CREATE TABLE `vehiclereading` (
  `VehicleReadingId` int(11) NOT NULL auto_increment,
  `VehicleReadingStatus` varchar(10) NOT NULL,
  `VehicleId` int(11) NOT NULL,
  `Reading` int(11) NOT NULL,
  `DOR` varchar(20) NOT NULL,
  `Remarks` text NOT NULL,
  `DOE` varchar(20) NOT NULL,
  `DOL` varchar(20) NOT NULL,
  `DOD` varchar(20) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  PRIMARY KEY  (`VehicleReadingId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vehiclereading`
--


-- --------------------------------------------------------

--
-- Table structure for table `vehicleroute`
--

CREATE TABLE `vehicleroute` (
  `VehicleRouteId` int(11) NOT NULL auto_increment,
  `Session` varchar(10) NOT NULL,
  `VehicleRouteStatus` varchar(10) NOT NULL,
  `VehicleRouteName` varchar(100) NOT NULL,
  `VehicleId` int(11) NOT NULL,
  `Route` text NOT NULL,
  `RouteTo` int(11) NOT NULL,
  `VehicleRouteRemarks` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`VehicleRouteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vehicleroute`
--


-- --------------------------------------------------------

--
-- Table structure for table `vehicleroutedetail`
--

CREATE TABLE `vehicleroutedetail` (
  `VehicleRouteDetailId` int(11) NOT NULL auto_increment,
  `VehicleRouteDetailStatus` varchar(10) NOT NULL,
  `VehicleRouteId` int(11) NOT NULL,
  `RouteStoppageId` int(11) NOT NULL,
  `Students` text NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  PRIMARY KEY  (`VehicleRouteDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vehicleroutedetail`
--


-- --------------------------------------------------------

--
-- Table structure for table `visitorbook`
--

CREATE TABLE `visitorbook` (
  `VisitorBookId` int(11) NOT NULL auto_increment,
  `VisitorBookStatus` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `NoOfPeople` int(11) NOT NULL,
  `Mobile` varchar(10) NOT NULL,
  `Address` text NOT NULL,
  `Purpose` text NOT NULL,
  `Description` text NOT NULL,
  `InDateTime` varchar(10) NOT NULL,
  `OutDateTime` varchar(10) NOT NULL,
  `DOE` varchar(10) NOT NULL,
  `DOEUsername` varchar(100) NOT NULL,
  `DOL` varchar(10) NOT NULL,
  `DOLUsername` varchar(100) NOT NULL,
  `DOD` varchar(10) NOT NULL,
  `DODUsername` varchar(100) NOT NULL,
  `Remarks` text NOT NULL,
  PRIMARY KEY  (`VisitorBookId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `visitorbook`
--


-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

CREATE TABLE `lang` (
  `LanguageId` int(11) NOT NULL auto_increment,
  `LanguageName` varchar(100) NOT NULL,
  PRIMARY KEY  (`LanguageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `lang`
--

INSERT INTO `lang` (`LanguageId`, `LanguageName`) VALUES
(1, 'हिंदी'),
(2, 'Dutch'),
(3, 'German'),
(4, 'Portuguese'),
(5, 'Spanish'),
(6, 'French');

-- --------------------------------------------------------

--
-- Table structure for table `phrase`
--

CREATE TABLE `phrase` (
  `PhraseId` int(11) NOT NULL auto_increment,
  `Phrase` text NOT NULL,
  PRIMARY KEY  (`PhraseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `phrase`
--

INSERT INTO `phrase` (`PhraseId`, `Phrase`) VALUES
(1, 'Front Office'),
(2, 'Call & Follow-up'),
(3, 'Other Call'),
(4, 'Enquiry'),
(5, 'Complaint'),
(6, 'Visitor Book'),
(7, 'Admission'),
(8, 'Registration'),
(9, 'Promotion'),
(10, 'Update Fee'),
(11, 'Reports'),
(12, 'Admission Report'),
(13, 'Fee Payment'),
(14, 'Transaction'),
(15, 'Expense'),
(16, 'Income'),
(17, 'Attendance'),
(18, 'Staff Attendance'),
(19, 'Student Attendance'),
(20, 'Staff Attendance Report'),
(21, 'Student Attendance Report'),
(22, 'Transport'),
(23, 'Transport Route'),
(24, 'Exam'),
(25, 'Scholastic Grade'),
(26, 'Co Scholastic Grade'),
(27, 'Exam Report'),
(28, 'Manage Staff'),
(29, 'Library'),
(30, 'Manage Books'),
(31, 'Issue & Return'),
(32, 'Dispatch & Receiving'),
(33, 'Dispatch'),
(34, 'Receiving'),
(35, 'Stock'),
(36, 'Manage Stock'),
(37, 'Stock Transfer'),
(38, 'Purchase Material'),
(39, 'Supplier'),
(40, 'Purchase'),
(41, 'Issue Material'),
(42, 'Stock Report'),
(43, 'School Material'),
(44, 'Issue Report'),
(45, 'Purchase Report'),
(46, 'SMS'),
(47, 'Setting'),
(48, 'General Setting'),
(49, 'Master Entry'),
(50, 'Manage User'),
(51, 'Manage Accounts'),
(52, 'Manage Class'),
(53, 'Manage Subject'),
(54, 'Manage Exam'),
(55, 'Manage SC Area'),
(56, 'Manage SC Indicator'),
(57, 'Manage Fee'),
(58, 'Salary Head'),
(59, 'Salary Structure'),
(60, 'School Material'),
(61, 'Manage Location'),
(62, 'Header & Footer'),
(63, 'Permission'),
(64, 'Current Session'),
(65, 'Navigation'),
(66, 'Graph Report'),
(67, 'Calendar');

-- --------------------------------------------------------

--
-- Table structure for table `timezone`
--

CREATE TABLE `timezone` (
  `TimezoneId` int(11) NOT NULL auto_increment,
  `TimezoneName` varchar(100) NOT NULL,
  PRIMARY KEY  (`TimezoneId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=420 ;

--
-- Dumping data for table `timezone`
--

INSERT INTO `timezone` (`TimezoneId`, `TimezoneName`) VALUES
(1, 'Africa/Abidjan'),
(2, 'Africa/Accra'),
(3, 'Africa/Addis_Ababa'),
(4, 'Africa/Algiers'),
(5, 'Africa/Asmara'),
(6, 'Africa/Asmera'),
(7, 'Africa/Bamako'),
(8, 'Africa/Bangui'),
(9, 'Africa/Banjul'),
(10, 'Africa/Bissau'),
(11, 'Africa/Blantyre'),
(12, 'Africa/Brazzaville'),
(13, 'Africa/Bujumbura'),
(14, 'Africa/Cairo'),
(15, 'Africa/Casablanca'),
(16, 'Africa/Ceuta'),
(17, 'Africa/Conakry'),
(18, 'Africa/Dakar'),
(19, 'Africa/Dar_es_Salaam'),
(20, 'Africa/Djibouti'),
(21, 'Africa/Douala'),
(22, 'Africa/El_Aaiun'),
(23, 'Africa/Freetown'),
(24, 'Africa/Gaborone'),
(25, 'Africa/Harare'),
(26, 'Africa/Johannesburg'),
(27, 'Africa/Juba'),
(28, 'Africa/Kampala'),
(29, 'Africa/Khartoum'),
(30, 'Africa/Kigali'),
(31, 'Africa/Kinshasa'),
(32, 'Africa/Lagos'),
(33, 'Africa/Libreville'),
(34, 'Africa/Lome'),
(35, 'Africa/Luanda'),
(36, 'Africa/Lubumbashi'),
(37, 'Africa/Lusaka'),
(38, 'Africa/Malabo'),
(39, 'Africa/Maputo'),
(40, 'Africa/Maseru'),
(41, 'Africa/Mbabane'),
(42, 'Africa/Mogadishu'),
(43, 'Africa/Monrovia'),
(44, 'Africa/Nairobi'),
(45, 'Africa/Ndjamena'),
(46, 'Africa/Niamey'),
(47, 'Africa/Nouakchott'),
(48, 'Africa/Ouagadougou'),
(49, 'Africa/Porto-Novo'),
(50, 'Africa/Sao_Tome'),
(51, 'Africa/Timbuktu'),
(52, 'Africa/Tripoli'),
(53, 'Africa/Tunis'),
(54, 'Africa/Windhoek'),
(55, 'America/Adak'),
(56, 'America/Anchorage'),
(57, 'America/Anguilla'),
(58, 'America/Antigua'),
(59, 'America/Araguaina'),
(60, 'America/Argentina/Buenos_Aires'),
(61, 'America/Argentina/Catamarca'),
(62, 'America/Argentina/ComodRivadavia'),
(63, 'America/Argentina/Cordoba'),
(64, 'America/Argentina/Jujuy'),
(65, 'America/Argentina/La_Rioja'),
(66, 'America/Argentina/Mendoza'),
(67, 'America/Argentina/Rio_Gallegos'),
(68, 'America/Argentina/Salta'),
(69, 'America/Argentina/San_Juan'),
(70, 'America/Argentina/San_Luis'),
(71, 'America/Argentina/Tucuman'),
(72, 'America/Argentina/Ushuaia'),
(73, 'America/Aruba'),
(74, 'America/Asuncion'),
(75, 'America/Atikokan'),
(76, 'America/Atka'),
(77, 'America/Bahia'),
(78, 'America/Bahia_Banderas'),
(79, 'America/Barbados'),
(80, 'America/Belem'),
(81, 'America/Belize'),
(82, 'America/Blanc-Sablon'),
(83, 'America/Boa_Vista'),
(84, 'America/Bogota'),
(85, 'America/Boise'),
(86, 'America/Buenos_Aires'),
(87, 'America/Cambridge_Bay'),
(88, 'America/Campo_Grande'),
(89, 'America/Cancun'),
(90, 'America/Caracas'),
(91, 'America/Catamarca'),
(92, 'America/Cayenne'),
(93, 'America/Cayman'),
(94, 'America/Chicago'),
(95, 'America/Chihuahua'),
(96, 'America/Coral_Harbour'),
(97, 'America/Cordoba'),
(98, 'America/Costa_Rica'),
(99, 'America/Creston'),
(100, 'America/Cuiaba'),
(101, 'America/Curacao'),
(102, 'America/Danmarkshavn'),
(103, 'America/Dawson'),
(104, 'America/Dawson_Creek'),
(105, 'America/Denver'),
(106, 'America/Detroit'),
(107, 'America/Dominica'),
(108, 'America/Edmonton'),
(109, 'America/Eirunepe'),
(110, 'America/El_Salvador'),
(111, 'America/Ensenada'),
(112, 'America/Fort_Wayne'),
(113, 'America/Fortaleza'),
(114, 'America/Glace_Bay'),
(115, 'America/Godthab'),
(116, 'America/Goose_Bay'),
(117, 'America/Grand_Turk'),
(118, 'America/Grenada'),
(119, 'America/Guadeloupe'),
(120, 'America/Guatemala'),
(121, 'America/Guayaquil'),
(122, 'America/Guyana'),
(123, 'America/Halifax'),
(124, 'America/Havana'),
(125, 'America/Hermosillo'),
(126, 'America/Indiana/Indianapolis'),
(127, 'America/Indiana/Knox'),
(128, 'America/Indiana/Marengo'),
(129, 'America/Indiana/Petersburg'),
(130, 'America/Indiana/Tell_City'),
(131, 'America/Indiana/Vevay'),
(132, 'America/Indiana/Vincennes'),
(133, 'America/Indiana/Winamac'),
(134, 'America/Indianapolis'),
(135, 'America/Inuvik'),
(136, 'America/Iqaluit'),
(137, 'America/Jamaica'),
(138, 'America/Jujuy'),
(139, 'America/Juneau'),
(140, 'America/Kentucky/Louisville'),
(141, 'America/Kentucky/Monticello'),
(142, 'America/Knox_IN'),
(143, 'America/Kralendijk'),
(144, 'America/La_Paz'),
(145, 'America/Lima'),
(146, 'America/Los_Angeles'),
(147, 'America/Louisville'),
(148, 'America/Lower_Princes'),
(149, 'America/Maceio'),
(150, 'America/Managua'),
(151, 'America/Manaus'),
(152, 'America/Marigot'),
(153, 'America/Martinique'),
(154, 'America/Matamoros'),
(155, 'America/Mazatlan'),
(156, 'America/Mendoza'),
(157, 'America/Menominee'),
(158, 'America/Merida'),
(159, 'America/Metlakatla'),
(160, 'America/Mexico_City'),
(161, 'America/Miquelon'),
(162, 'America/Moncton'),
(163, 'America/Monterrey'),
(164, 'America/Montevideo'),
(165, 'America/Montreal'),
(166, 'America/Montserrat'),
(167, 'America/Nassau'),
(168, 'America/New_York'),
(169, 'America/Nipigon'),
(170, 'America/Nome'),
(171, 'America/Noronha'),
(172, 'America/North_Dakota/Beulah'),
(173, 'America/North_Dakota/Center'),
(174, 'America/North_Dakota/New_Salem'),
(175, 'America/Ojinaga'),
(176, 'America/Panama'),
(177, 'America/Pangnirtung'),
(178, 'America/Paramaribo'),
(179, 'America/Phoenix'),
(180, 'America/Port_of_Spain'),
(181, 'America/Port-au-Prince'),
(182, 'America/Porto_Acre'),
(183, 'America/Porto_Velho'),
(184, 'America/Puerto_Rico'),
(185, 'America/Rainy_River'),
(186, 'America/Rankin_Inlet'),
(187, 'America/Recife'),
(188, 'America/Regina'),
(189, 'America/Resolute'),
(190, 'America/Rio_Branco'),
(191, 'America/Rosario'),
(192, 'America/Santa_Isabel'),
(193, 'America/Santarem'),
(194, 'America/Santiago'),
(195, 'America/Santo_Domingo'),
(196, 'America/Sao_Paulo'),
(197, 'America/Scoresbysund'),
(198, 'America/Shiprock'),
(199, 'America/Sitka'),
(200, 'America/St_Barthelemy'),
(201, 'America/St_Johns'),
(202, 'America/St_Kitts'),
(203, 'America/St_Lucia'),
(204, 'America/St_Thomas'),
(205, 'America/St_Vincent'),
(206, 'America/Swift_Current'),
(207, 'America/Tegucigalpa'),
(208, 'America/Thule'),
(209, 'America/Thunder_Bay'),
(210, 'America/Tijuana'),
(211, 'America/Toronto'),
(212, 'America/Tortola'),
(213, 'America/Vancouver'),
(214, 'America/Virgin'),
(215, 'America/Whitehorse'),
(216, 'America/Winnipeg'),
(217, 'America/Yakutat'),
(218, 'America/Yellowknife'),
(219, 'Antarctica/Casey'),
(220, 'Antarctica/Davis'),
(221, 'Antarctica/DumontDUrville'),
(222, 'Antarctica/Macquarie'),
(223, 'Antarctica/Mawson'),
(224, 'Antarctica/McMurdo'),
(225, 'Antarctica/Palmer'),
(226, 'Antarctica/Rothera'),
(227, 'Antarctica/South_Pole'),
(228, 'Antarctica/Syowa'),
(229, 'Antarctica/Vostok'),
(230, 'Arctic/Longyearbyen'),
(231, 'Asia/Aden'),
(232, 'Asia/Amman'),
(233, 'Asia/Anadyr'),
(234, 'Asia/Aqtau'),
(235, 'Asia/Aqtobe'),
(236, 'Asia/Ashkhabad'),
(237, 'Asia/Baghdad'),
(238, 'Asia/Bahrain'),
(239, 'Asia/Baku'),
(240, 'Asia/Beirut'),
(241, 'Asia/Bishkek'),
(242, 'Asia/Brunei'),
(243, 'Asia/Calcutta'),
(244, 'Asia/Chongqing'),
(245, 'Asia/Chungking'),
(246, 'Asia/Colombo'),
(247, 'Asia/Dacca'),
(248, 'Asia/Dhaka'),
(249, 'Asia/Dili'),
(250, 'Asia/Dubai'),
(251, 'Asia/Dushanbe'),
(252, 'Asia/Harbin'),
(253, 'Asia/Hebron'),
(254, 'Asia/Ho_Chi_Minh'),
(255, 'Asia/Hong_Kong'),
(256, 'Asia/Irkutsk'),
(257, 'Asia/Istanbul'),
(258, 'Asia/Jakarta'),
(259, 'Asia/Jayapura'),
(260, 'Asia/Kabul'),
(261, 'Asia/Kamchatka'),
(262, 'Asia/Karachi'),
(263, 'Asia/Kashgar'),
(264, 'Asia/Katmandu'),
(265, 'Asia/Khandyga'),
(266, 'Asia/Kolkata'),
(267, 'Asia/Krasnoyarsk'),
(268, 'Asia/Kuching'),
(269, 'Asia/Kuwait'),
(270, 'Asia/Macao'),
(271, 'Asia/Macau'),
(272, 'Asia/Makassar'),
(273, 'Asia/Manila'),
(274, 'Asia/Muscat'),
(275, 'Asia/Nicosia'),
(276, 'Asia/Novosibirsk'),
(277, 'Asia/Omsk'),
(278, 'Asia/Oral'),
(279, 'Asia/Phnom_Penh'),
(280, 'Asia/Pyongyang'),
(281, 'Asia/Qatar'),
(282, 'Asia/Qyzylorda'),
(283, 'Asia/Rangoon'),
(284, 'Asia/Saigon'),
(285, 'Asia/Sakhalin'),
(286, 'Asia/Samarkand'),
(287, 'Asia/Seoul'),
(288, 'Asia/Singapore'),
(289, 'Asia/Taipei'),
(290, 'Asia/Tashkent'),
(291, 'Asia/Tbilisi'),
(292, 'Asia/Tel_Aviv'),
(293, 'Asia/Thimbu'),
(294, 'Asia/Thimphu'),
(295, 'Asia/Tokyo'),
(296, 'Asia/Ulaanbaatar'),
(297, 'Asia/Ulan_Bator'),
(298, 'Asia/Urumqi'),
(299, 'Asia/Ust-Nera'),
(300, 'Asia/Vladivostok'),
(301, 'Asia/Yakutsk'),
(302, 'Asia/Yekaterinburg'),
(303, 'Asia/Yerevan'),
(304, 'Atlantic/Azores'),
(305, 'Atlantic/Canary'),
(306, 'Atlantic/Cape_Verde'),
(307, 'Atlantic/Faeroe'),
(308, 'Atlantic/Faroe'),
(309, 'Atlantic/Madeira'),
(310, 'Atlantic/Reykjavik'),
(311, 'Atlantic/South_Georgia'),
(312, 'Atlantic/St_Helena'),
(313, 'Australia/ACT'),
(314, 'Australia/Brisbane'),
(315, 'Australia/Broken_Hill'),
(316, 'Australia/Canberra'),
(317, 'Australia/Currie'),
(318, 'Australia/Eucla'),
(319, 'Australia/Hobart'),
(320, 'Australia/LHI'),
(321, 'Australia/Lindeman'),
(322, 'Australia/Melbourne'),
(323, 'Australia/North'),
(324, 'Australia/NSW'),
(325, 'Australia/Perth'),
(326, 'Australia/South'),
(327, 'Australia/Sydney'),
(328, 'Australia/Tasmania'),
(329, 'Australia/Victoria'),
(330, 'Australia/Yancowinna'),
(331, 'Europe/Amsterdam'),
(332, 'Europe/Athens'),
(333, 'Europe/Belfast'),
(334, 'Europe/Belgrade'),
(335, 'Europe/Berlin'),
(336, 'Europe/Brussels'),
(337, 'Europe/Bucharest'),
(338, 'Europe/Budapest'),
(339, 'Europe/Busingen'),
(340, 'Europe/Copenhagen'),
(341, 'Europe/Dublin'),
(342, 'Europe/Gibraltar'),
(343, 'Europe/Guernsey'),
(344, 'Europe/Isle_of_Man'),
(345, 'Europe/Istanbul'),
(346, 'Europe/Jersey'),
(347, 'Europe/Kaliningrad'),
(348, 'Europe/Lisbon'),
(349, 'Europe/Ljubljana'),
(350, 'Europe/London'),
(351, 'Europe/Luxembourg'),
(352, 'Europe/Malta'),
(353, 'Europe/Mariehamn'),
(354, 'Europe/Minsk'),
(355, 'Europe/Monaco'),
(356, 'Europe/Nicosia'),
(357, 'Europe/Oslo'),
(358, 'Europe/Paris'),
(359, 'Europe/Podgorica'),
(360, 'Europe/Riga'),
(361, 'Europe/Rome'),
(362, 'Europe/Samara'),
(363, 'Europe/San_Marino'),
(364, 'Europe/Simferopol'),
(365, 'Europe/Skopje'),
(366, 'Europe/Sofia'),
(367, 'Europe/Stockholm'),
(368, 'Europe/Tirane'),
(369, 'Europe/Tiraspol'),
(370, 'Europe/Uzhgorod'),
(371, 'Europe/Vaduz'),
(372, 'Europe/Vienna'),
(373, 'Europe/Vilnius'),
(374, 'Europe/Volgograd'),
(375, 'Europe/Warsaw'),
(376, 'Europe/Zaporozhye'),
(377, 'Europe/Zurich'),
(378, 'Indian/Antananarivo'),
(379, 'Indian/Christmas'),
(380, 'Indian/Cocos'),
(381, 'Indian/Comoro'),
(382, 'Indian/Kerguelen'),
(383, 'Indian/Maldives'),
(384, 'Indian/Mauritius'),
(385, 'Indian/Mayotte'),
(386, 'Indian/Reunion'),
(387, 'Pacific/Apia'),
(388, 'Pacific/Chatham'),
(389, 'Pacific/Chuuk'),
(390, 'Pacific/Easter'),
(391, 'Pacific/Efate'),
(392, 'Pacific/Fakaofo'),
(393, 'Pacific/Fiji'),
(394, 'Pacific/Funafuti'),
(395, 'Pacific/Galapagos'),
(396, 'Pacific/Guadalcanal'),
(397, 'Pacific/Guam'),
(398, 'Pacific/Honolulu'),
(399, 'Pacific/Johnston'),
(400, 'Pacific/Kosrae'),
(401, 'Pacific/Kwajalein'),
(402, 'Pacific/Majuro'),
(403, 'Pacific/Marquesas'),
(404, 'Pacific/Nauru'),
(405, 'Pacific/Niue'),
(406, 'Pacific/Norfolk'),
(407, 'Pacific/Noumea'),
(408, 'Pacific/Palau'),
(409, 'Pacific/Pitcairn'),
(410, 'Pacific/Pohnpei'),
(411, 'Pacific/Ponape'),
(412, 'Pacific/Rarotonga'),
(413, 'Pacific/Saipan'),
(414, 'Pacific/Samoa'),
(415, 'Pacific/Tahiti'),
(416, 'Pacific/Tongatapu'),
(417, 'Pacific/Truk'),
(418, 'Pacific/Wake'),
(419, 'Pacific/Wallis');

-- --------------------------------------------------------

--
-- Table structure for table `translate`
--

CREATE TABLE `translate` (
  `TranslateId` int(11) NOT NULL auto_increment,
  `LanguageId` int(11) NOT NULL,
  `Translation` text NOT NULL,
  PRIMARY KEY  (`TranslateId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `translate`
--

INSERT INTO `translate` (`TranslateId`, `LanguageId`, `Translation`) VALUES
(1, 1, '1**&#2347;&#2381;&#2352;&#2306;&#2335; &#2321;&#2347;&#2367;&#2360; \r||2**&#2325;&#2377;&#2354; &#2324;&#2352; &#2309;&#2344;&#2369;&#2357;&#2352;&#2381;&#2340;&#2368; \r||3**&#2309;&#2344;&#2381;&#2351; &#2325;&#2377;&#2354; \r||4**&#2346;&#2370;&#2331;&#2340;&#2366;&#2331; \r||5**&#2358;&#2367;&#2325;&#2366;&#2351;&#2340; \r||6**&#2357;&#2367;&#2332;&#2367;&#2335;&#2352; &#2348;&#2369;&#2325; \r||7**&#2319;&#2337;&#2350;&#2367;&#2358;&#2344; \r||8**&#2346;&#2306;&#2332;&#2368;&#2325;&#2352;&#2339; \r||9**&#2346;&#2342;&#2379;&#2344;&#2381;&#2344;&#2340;&#2367; \r||10**&#2309;&#2342;&#2381;&#2351;&#2340;&#2344; &#2358;&#2369;&#2354;&#2381;&#2325; \r||11**&#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||12**&#2319;&#2337;&#2350;&#2367;&#2358;&#2344; &#2325;&#2368; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||13**&#2358;&#2369;&#2354;&#2381;&#2325; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; \r||14**&#2335;&#2381;&#2352;&#2366;&#2306;&#2332;&#2376;&#2325;&#2381;&#2358;&#2344; \r||15**&#2357;&#2381;&#2351;&#2351; \r||16**&#2310;&#2351; \r||17**&#2313;&#2346;&#2360;&#2381;&#2341;&#2367;&#2340;&#2367; \r||18**&#2360;&#2381;&#2335;&#2366;&#2347; &#2313;&#2346;&#2360;&#2381;&#2341;&#2367;&#2340;&#2367; \r||19**&#2331;&#2366;&#2340;&#2381;&#2352; &#2313;&#2346;&#2360;&#2381;&#2341;&#2367;&#2340;&#2367; \r||20**&#2360;&#2381;&#2335;&#2366;&#2347; &#2313;&#2346;&#2360;&#2381;&#2341;&#2367;&#2340;&#2367; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||21**&#2331;&#2366;&#2340;&#2381;&#2352; &#2313;&#2346;&#2360;&#2381;&#2341;&#2367;&#2340;&#2367; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||22**&#2346;&#2352;&#2367;&#2357;&#2361;&#2344; \r||23**&#2346;&#2352;&#2367;&#2357;&#2361;&#2344; &#2335;&#2381;&#2352;&#2375;&#2344; \r||24**&#2346;&#2352;&#2368;&#2325;&#2381;&#2359;&#2366; \r||25**&#2358;&#2376;&#2325;&#2381;&#2359;&#2367;&#2325; &#2327;&#2381;&#2352;&#2375;&#2337; \r||26**&#2360;&#2361; &#2358;&#2376;&#2325;&#2381;&#2359;&#2367;&#2325; &#2327;&#2381;&#2352;&#2375;&#2337; \r||27**&#2346;&#2352;&#2368;&#2325;&#2381;&#2359;&#2366; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||28**&#2325;&#2352;&#2381;&#2350;&#2330;&#2366;&#2352;&#2367;&#2351;&#2379;&#2306; &#2325;&#2366; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2344; \r||29**&#2354;&#2366;&#2311;&#2348;&#2381;&#2352;&#2375;&#2352;&#2368; \r||30**&#2346;&#2369;&#2360;&#2381;&#2340;&#2325;&#2375;&#2306; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; &#2325;&#2352;&#2375;&#2306; \r||31**&#2309;&#2306;&#2325; &#2324;&#2352; &#2357;&#2366;&#2346;&#2360;&#2368; \r||32**&#2337;&#2367;&#2360;&#2381;&#2346;&#2376;&#2330; &#2357; &#2346;&#2381;&#2352;&#2366;&#2346;&#2381;&#2340; \r||33**&#2337;&#2367;&#2360;&#2381;&#2346;&#2376;&#2330; \r||34**&#2346;&#2381;&#2352;&#2366;&#2346;&#2381;&#2340; \r||35**&#2358;&#2375;&#2351;&#2352; \r||36**&#2360;&#2381;&#2335;&#2377;&#2325; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; \r||37**&#2358;&#2375;&#2351;&#2352; &#2335;&#2381;&#2352;&#2366;&#2306;&#2360;&#2347;&#2352; \r||38**&#2325;&#2381;&#2352;&#2351; &#2360;&#2366;&#2350;&#2327;&#2381;&#2352;&#2368; \r||39**&#2346;&#2381;&#2352;&#2342;&#2366;&#2351;&#2325; \r||40**&#2326;&#2352;&#2368;&#2342; \r||41**&#2350;&#2369;&#2342;&#2381;&#2342;&#2366; &#2360;&#2366;&#2350;&#2327;&#2381;&#2352;&#2368; \r||42**&#2360;&#2381;&#2335;&#2377;&#2325; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||43**&#2360;&#2381;&#2325;&#2370;&#2354; &#2360;&#2366;&#2350;&#2327;&#2381;&#2352;&#2368; \r||44**&#2350;&#2366;&#2350;&#2354;&#2375; &#2325;&#2368; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||45**&#2325;&#2381;&#2352;&#2351; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||46**&#2319;&#2360;&#2319;&#2350;&#2319;&#2360; \r||47**&#2360;&#2375;&#2335;&#2367;&#2306;&#2327; \r||48**&#2360;&#2366;&#2350;&#2366;&#2344;&#2381;&#2351; &#2360;&#2375;&#2335;&#2367;&#2306;&#2327; \r||49**&#2350;&#2366;&#2360;&#2381;&#2335;&#2352; &#2319;&#2306;&#2335;&#2381;&#2352;&#2368; \r||50**&#2313;&#2346;&#2351;&#2379;&#2327;&#2325;&#2352;&#2381;&#2340;&#2366; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; \r||51**&#2326;&#2366;&#2340;&#2379;&#2306; &#2325;&#2366; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2344; \r||52**&#2325;&#2325;&#2381;&#2359;&#2366; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; \r||53**&#2357;&#2367;&#2359;&#2351; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; \r||54**&#2346;&#2352;&#2368;&#2325;&#2381;&#2359;&#2366; &#2325;&#2366; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2344; \r||55**&#2309;&#2344;&#2369;&#2360;&#2370;&#2330;&#2367;&#2340; &#2332;&#2366;&#2340;&#2367; &#2325;&#2381;&#2359;&#2375;&#2340;&#2381;&#2352; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; &#2325;&#2352;&#2375;&#2306; \r||56**&#2309;&#2344;&#2369;&#2360;&#2370;&#2330;&#2367;&#2340; &#2332;&#2366;&#2340;&#2367; &#2360;&#2370;&#2330;&#2325; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; \r||57**&#2358;&#2369;&#2354;&#2381;&#2325; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; \r||58**&#2357;&#2375;&#2340;&#2344; &#2361;&#2375;&#2337; \r||59**&#2357;&#2375;&#2340;&#2344; &#2360;&#2306;&#2352;&#2330;&#2344;&#2366; \r||60**&#2360;&#2381;&#2325;&#2370;&#2354; &#2360;&#2366;&#2350;&#2327;&#2381;&#2352;&#2368; \r||61**&#2360;&#2381;&#2341;&#2366;&#2344; &#2346;&#2381;&#2352;&#2348;&#2306;&#2343;&#2367;&#2340; &#2325;&#2352;&#2375;&#2306; \r||62**&#2361;&#2376;&#2337;&#2352; &#2324;&#2352; &#2346;&#2366;&#2342; \r||63**&#2309;&#2344;&#2369;&#2350;&#2340;&#2367; \r||64**&#2357;&#2352;&#2381;&#2340;&#2350;&#2366;&#2344; &#2360;&#2340;&#2381;&#2352; \r||65**&#2344;&#2375;&#2357;&#2367;&#2327;&#2375;&#2358;&#2344; \r||66**&#2327;&#2381;&#2352;&#2366;&#2347; &#2352;&#2367;&#2346;&#2379;&#2352;&#2381;&#2335; \r||67**&#2325;&#2376;&#2354;&#2375;&#2306;&#2337;&#2352;'),
(2, 6, '1**Front Office \r||2**Call & Suivi \r||3**autre appel \r||4**Demande de renseignements \r||5**plainte \r||6**livre d''\r||7**admission \r||8**inscription \r||9**promotion \r||10**Mise à jour Fee \r||11**rapports \r||12**admission rapport \r||13**Paiement des droits \r||14**transaction \r||15**frais \r||16**revenu \r||17**présence \r||18**Participation du personnel \r||19**Participation des étudiants \r||20**Rapport du personnel de présence \r||21**Rapport de l''assiduité des élèves \r||22**transport \r||23**Transport Route \r||24**exam \r||25**Scholastic année \r||26**Co Scholastic année \r||27**Rapport d''examen \r||28**gérer du personnel \r||29**bibliothèque \r||30**gérer les livres \r||31**Question et de retour \r||32**Envoi et réception \r||33**dépêche \r||34**recevoir \r||35**stock \r||36**gérer Stock \r||37**Transfert de stock \r||38**Matériau achat \r||39**fournisseur \r||40**achat \r||41**problème Matériel \r||42**Rapport sur l''action \r||43**Matériel scolaire \r||44**Rapport d''émission \r||45**Rapport achat \r||46**SMS \r||47**Cadre \r||48**Cadre général \r||49**maître d''entrée \r||50**gérer l''utilisateur \r||51**gérer les comptes \r||52**gérer classe \r||53**gérer Sujet \r||54**gérer examen \r||55**Gérer Zone SC \r||56**Gérer SC Indicateur \r||57**gérer Fee \r||58**salaire chef \r||59**Structure des salaires \r||60**Matériel scolaire \r||61**gérer Lieu \r||62**En-tête et pied de page \r||63**autorisation \r||64**session en cours \r||65**navigation \r||66**Rapport graphique \r||67**calendrier'),
(3, 5, '1**Front Office \r||2**Call & Seguimiento \r||3**otro Call \r||4**Consulta \r||5**Queja \r||6**libro de Visitantes \r||7**Admisión \r||8**registro \r||9**Promoción \r||10**Tarifa de Actualización \r||11**Informes \r||12**Informe de Admisión \r||13**Cargo por pago \r||14**Transacción \r||15**gastos \r||16**Ingresos \r||17**Asistencia \r||18**El personal de asistencia \r||19**Asistencia Estudiantil \r||20**Personal Informe de asistencia \r||21**Informe de Asistencia Estudiantil \r||22**Transporte \r||23**Ruta de Transporte \r||24**examen \r||25**Scholastic Grado \r||26**Co Scholastic Grado \r||27**Informe de examen \r||28**Gestionar personal \r||29**Biblioteca \r||30**administrar libros \r||31**Edición y vuelta \r||32**Envío y recepción \r||33**Despacho \r||34**Recibir \r||35**Stock \r||36**Gestionar Stock \r||37**Stock Transfer \r||38**material de Compra \r||39**Proveedor \r||40**Compra \r||41**material Issue \r||42**Stock Informe \r||43**material de la Escuela \r||44**Informe de Cuestiones \r||45**Informe Compra \r||46**sMS \r||47**ajuste \r||48**Configuración general \r||49**Entrada Maestro \r||50**Gestionar usuario \r||51**administrar cuentas \r||52**Gestionar Clase \r||53**Gestionar Asunto \r||54**Gestionar Exam \r||55**Gestionar Area SC \r||56**Gestionar SC Indicador \r||57**Gestionar Fee \r||58**Jefe Salario \r||59**Estructura salarial \r||60**material de la Escuela \r||61**Gestionar Ubicación \r||62**Encabezado y pie de página \r||63**permiso \r||64**Sesión actual \r||65**Navegación \r||66**Gráfico Informe \r||67**Calendario'),
(4, 2, '1**Front Office \r||2**Bel & Follow-up \r||3**andere Call \r||4**Aanvraag \r||5**klacht \r||6**bezoeker Boek \r||7**toelating \r||8**registratie \r||9**promotie \r||10**Fee-update \r||11**rapporten \r||12**toelating Report \r||13**vergoeding betalen \r||14**transactie \r||15**Expense \r||16**inkomen \r||17**Aanwezigheid \r||18**personeel Aanwezigheid \r||19**Aanwezigheid \r||20**Personeel Rapport Aanwezigheid \r||21**Student Rapport Aanwezigheid \r||22**Transport \r||23**Transport Route \r||24**examen \r||25**Scholastic Grade \r||26**Co Scholastic Grade \r||27**examen Report \r||28**Beheer Personeel \r||29**bibliotheek \r||30**Boeken beheren \r||31**Kwestie & Return \r||32**Dispatch & ontvangen \r||33**Dispatch \r||34**ontvangende \r||35**voorraad \r||36**Beheer Stock \r||37**Stock Transfer \r||38**aankoop Material \r||39**Leverancier \r||40**aankoop \r||41**kwestie Materiaal \r||42**Stock Report \r||43**School Materiaal \r||44**issue Report \r||45**aankoop Report \r||46**SMS \r||47**instelling \r||48**algemene instelling \r||49**Master Entry \r||50**Beheer Gebruiker \r||51**Accounts beheren \r||52**Beheer Class \r||53**Beheer Onderwerp \r||54**Beheer Examen \r||55**Beheer SC Area \r||56**Beheer SC Indicator \r||57**Beheer Fee \r||58**salaris Hoofd \r||59**salarisstructuur \r||60**School Materiaal \r||61**Beheer Locatie \r||62**Koptekst en voettekst \r||63**toestemming \r||64**huidige sessie \r||65**Navigatie \r||66**grafiek Report \r||67**Kalender'),
(5, 3, '1**Front Office \r||2**Call & Follow-up \r||3**andere Anruf \r||4**Anfrage \r||5**Beschwerde \r||6**Besucher buchen \r||7**Eintritt \r||8**Anmeldung \r||9**Förderung \r||10**Update Fee \r||11**Berichte \r||12**Eintritt Bericht \r||13**Gebührenzahlung \r||14**Transaktion \r||15**Ausgabe \r||16**Einkommen \r||17**Teilnahme \r||18**Personal Teilnahme \r||19**Schülerzahl \r||20**Mitarbeiter Anwesenheitsbericht \r||21**Schülerzahl Bericht \r||22**Transport \r||23**Transportroute \r||24**Prüfung \r||25**Scholastic Grade \r||26**Co Scholastic Grade \r||27**Untersuchungsbericht \r||28**Mitarbeiter verwalten \r||29**Bibliothek \r||30**Bücher verwalten \r||31**Frage & Return \r||32**Versand & Empfang \r||33**Versand \r||34**Empfang \r||35**Lager \r||36**Auf verwalten \r||37**Umlagerung \r||38**Kauf-Material \r||39**Lieferant \r||40**Kauf \r||41**Ausgabe-Material \r||42**stock Report \r||43**Schulmaterial \r||44**Problem melden \r||45**Kauf Bericht \r||46**SMS \r||47**Einstellung \r||48**Allgemeine Einstellung \r||49**Master-Eintrag \r||50**Benutzer verwalten \r||51**Konten verwalten \r||52**Klasse verwalten \r||53**Betreff verwalten \r||54**Exam verwalten \r||55**Verwalten SC Umgebung \r||56**SC-Anzeige verwalten \r||57**Fee verwalten \r||58**Gehalt Leiter \r||59**Gehaltsstruktur \r||60**Schulmaterial \r||61**Ort verwalten \r||62**Kopf-und Fußzeile \r||63**Erlaubnis \r||64**aktuelle Sitzung \r||65**Navigation \r||66**Graph Bericht \r||67**Kalender'),
(6, 4, '1**Front Office \r||2**Ligue e Acompanhamento \r||3**outros Chamada \r||4**Inquérito \r||5**queixa \r||6**Livro de Visitas \r||7**admissão \r||8**Inscrição \r||9**promoção \r||10**Taxa de atualização \r||11**relatórios \r||12**Relatório de admissão \r||13**taxa de pagamento \r||14**transação \r||15**despesa \r||16**renda \r||17**Presença \r||18**Atendimento pessoal \r||19**comparecimento do Aluno \r||20**Relatório do Corpo Técnico de Atendimento \r||21**Relatório de Frequência Student \r||22**transporte \r||23**Itinerários \r||24**exame \r||25**Scholastic Grade \r||26**Co Scholastic Grade \r||27**Relatório de exame \r||28**Gerenciar equipe \r||29**biblioteca \r||30**Gerenciar livros \r||31**Emissão & Return \r||32**Despacho e Recebimento \r||33**Despacho \r||34**receber \r||35**Banco \r||36**Controle Stock \r||37**Transferência de estoque \r||38**compra de materiais \r||39**fornecedor \r||40**compra \r||41**Emissão de materiais \r||42**Relatório de estoque \r||43**material escolar \r||44**Reportagem Edição \r||45**Relatório de Compra \r||46**SMS \r||47**definição \r||48**Ajustes Gerais \r||49**Mestre entrada \r||50**Gerenciar usuário \r||51**Gerenciar Contas \r||52**Gerenciar Classe \r||53**Gerenciar Assunto \r||54**Gerenciar Exame \r||55**Gerenciar Área SC \r||56**Gerenciar Indicador SC \r||57**Gerenciar Fee \r||58**salário Cabeça \r||59**Estrutura salarial \r||60**material escolar \r||61**Gerenciar Localização \r||62**Cabeçalho e Rodapé \r||63**permissão \r||64**Sessão Atual \r||65**Navegação \r||66**gráfico Relatório \r||67**Calendário');
