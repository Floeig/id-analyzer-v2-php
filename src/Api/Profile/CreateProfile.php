<?php

namespace IDAnalyzer2\Api\Profile;

use IDAnalyzer2\ApiBase;
use IDAnalyzer2\RequestPayload;
use IDAnalyzer2\SDKException;

class CreateProfile extends ApiBase
{
    public string $uri = "/profile";
    public string $method = "POST";

    function __construct()
    {
        $this->initFields([
            RequestPayload::Field('name', 'string', true, "My Profile", 'Profile Name'),
            RequestPayload::Field('canvasSize', 'number', true, 1500, 'Canvas Size in pixels, input image larger than this size will be scaled down before further processing, reduced image size will improve inference time but reduce result accuracy.'),
            RequestPayload::Field('orientationCorrection', 'boolean', true, true, 'Correct image orientation for rotated images'),
            RequestPayload::Field('objectDetection', 'boolean', true, true, 'Enable to automatically detect and return the locations of signature, document and face.'),
            RequestPayload::Field('AAMVABarcodeParsing', 'boolean', true, true, 'Enable to parse AAMVA barcode for US/CA ID/DL. Disable this to improve performance if you are not planning on scanning ID/DL from US or Canada.'),
            RequestPayload::Field('saveResult', 'boolean', true, true, 'Whether transaction results should be saved'),
            RequestPayload::Field('saveImage', 'boolean', true, true, 'If saveResult is enabled, whether output images should also be saved on cloud.'),
            RequestPayload::Field('outputImage', 'boolean', true, true, 'Whether to return output image as part of API response'),
            RequestPayload::Field('outputType', 'string', true, 'url', 'Output processed image in either "base64" or "url".'),
            RequestPayload::Field('crop', 'boolean', true, false, 'Enable to automatically remove any irrelevant pixels from the uploaded image before saving and outputting the final image.'),
            RequestPayload::Field('advancedCrop', 'boolean', true, true, 'Enable to use advanced deskew feature on documents that are sheared.'),
            RequestPayload::Field('outputSize', 'number', true, 1000, 'Maximum pixel width/height for output & saved image.'),
            RequestPayload::Field('inferFullName', 'boolean', true, true, 'Generate a full name field using parsed first name, middle name and last name.'),
            RequestPayload::Field('splitFirstName', 'boolean', true, false, 'If first name contains more than one word, move second word onwards into middle name field.'),
            RequestPayload::Field('transactionAuditReport', 'boolean', true, false, 'Enable to generate a detailed PDF audit report for every transaction.'),
            RequestPayload::Field('timezone', 'string', true, "UTC", 'Set timezone for audit reports. If left blank, UTC will be used. Refer to https://en.wikipedia.org/wiki/List_of_tz_database_time_zones TZ database name list.'),
            RequestPayload::Field('obscure', 'array', true, [], 'A list of data fields key to be redacted before transaction storage, these fields will also be blurred from output & saved image.'),
            RequestPayload::Field('webhook', 'string', true, '', 'Enter a server URL to listen for Docupass verification and scan transaction results'),
            RequestPayload::Field('thresholds', 'object', true, [
                'face' => 0.5,
                'nameDualSide' => 0.5,
                'nameVerification' => 0.7,
                'addressVerification' => 0.9,
                'imageForgery' => 0.5,
                'textForgery' => 0.5,
                'recapture' => 0.5,
                'screenDetection' => 0.4,
                'lowTextConfidence' => 0.3,
                'artificialImage' => 0.5,
                'artificialText' => 0.5,
                'faceIdentical' => 0.5,
                'smallImage' => 0.5,
                'blurryImage' => 0.5,
                'cameraPerspective' => 0.5,
                'faceLiveness' => 0.2,
                'faceRecapture' => 0.5,
                'glareDetect' => 0.45,
                'blackWhite' => 0.98,
            ], 'Control the threshold of Document Validation Components, numbers should be float between 0 to 1.'),
            RequestPayload::Field('decisionTrigger', 'object', true, [
                'review' => 1,
                'reject' => 1,
            ], 'For every failed validation'),
            RequestPayload::Field('decisions', 'object', true, [
                'UNRECOGNIZED_DOCUMENT' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'UNRECOGNIZED_BACK_DOCUMENT' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'UNRECOGNIZED_BACK_BARCODE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'INVALID_BACK_DOCUMENT' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'SELFIE_FACE_NOT_FOUND' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'SELFIE_MULTIPLE_FACES' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOCUMENT_FACE_NOT_FOUND' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOCUMENT_FACE_LANDMARK_ERR' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'SELFIE_FACE_LANDMARK_ERR' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'INTERNAL_FACE_VERIFICATION_ERR' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'FACE_MISMATCH' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'FACE_IDENTICAL' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'FACE_LIVENESS_ERR' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'RECAPTURED_FACE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOCUMENT_COUNTRY_MISMATCH' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOCUMENT_STATE_MISMATCH' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOCUMENT_NAME_MISMATCH' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOCUMENT_DOB_MISMATCH' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_EXPIRY_DATE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_ISSUE_DATE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_BIRTH_DATE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_DOCUMENT_NUMBER' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_PERSONAL_NUMBER' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_ADDRESS' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_POSTCODE' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_NAME' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_LOCAL_NAME' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_GENDER' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_HEIGHT' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_WEIGHT' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_HAIR_COLOR' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_EYE_COLOR' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_RESTRICTIONS' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_VEHICLE_CLASS' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_ENDORSEMENT' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'MISSING_BUSINESS_REGISTRATION_NUMBER' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_BUSINESS_NAME' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'MISSING_ENTITY_TYPE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'UNDER_18' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'UNDER_19' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'UNDER_20' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'UNDER_21' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'DOCUMENT_EXPIRED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'NAME_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'DOB_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'AGE_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'ID_NUMBER_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'ADDRESS_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'POSTCODE_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'TYPE_NOT_ACCEPTED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'COUNTRY_NOT_ACCEPTED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'STATE_NOT_ACCEPTED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'RECAPTURED_DOCUMENT' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'SCREEN_DETECTED' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'IMAGE_FORGERY' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'FEATURE_VERIFICATION_FAILED' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'IMAGE_EDITED' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'AML_SANCTION' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'AML_CRIME' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'AML_PEP' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'LOW_TEXT_CONFIDENCE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'FAKE_ID' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'ARTIFICIAL_IMAGE' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'ARTIFICIAL_TEXT' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'TEXT_FORGERY' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'IP_COUNTRY_MISMATCH' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'IMAGE_TOO_SMALL' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'IMAGE_TOO_BLURRY' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'INVALID_CAMERA_PERSPECTIVE' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'CHECK_DIGIT_FAILED' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => 0,
                    'weight' => 1,
                ],
                'BLACK_WHITE_DOCUMENT' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'GLARE_DETECTED' => [
                    'enabled' => false,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'PHYSICAL_DOCUMENT_MISSING' => [
                    'enabled' => true,
                    'review' => 0,
                    'reject' => -1,
                    'weight' => 1,
                ],
                'UNKNOWN' => [
                    'enabled' => true,
                    'review' => -1,
                    'reject' => -1,
                    'weight' => 1,
                ],
            ], 'Enable/Disable and fine-tune how each Document Validation Component affects the final decision.'),
            RequestPayload::Field('docupass', 'object', true, [
                'companyName' => '',
                'welcomeMessage' => '',
                'logoURL' => '',
                'allowIframe' => true,
                'restrictDevice' => 0,
                'allowFileUpload' => false,
                'documentCaptureMode' => 0,
                'faceCaptureMode' => 0,
                'documentSide' => 0,
                'cameraMode' => 0,
                'reviewData' => false,
                'maxAttempt' => 2,
                'trackGps' => false,
                'acceptUrl' => '',
                'reviewUrl' => '',
                'rejectUrl' => '',
                'expireUrl' => '',
                'phoneVerification' => 0,
                'smsContent' => 'Identity Verification Link: $u',
                'customField' => [
                ],
                'customDocuPassURL' => '',
                'expiry' => 0,
                'qrColor' => '000000',
                'qrBGColor' => 'FFFFFF',
                'qrSize' => 8,
                'qrMargin' => 8,
                'docupassAuditReport' => false,
            ], 'Docupass express identity verification / e-signature module settings'),
            RequestPayload::Field('acceptedDocuments', 'object', true, [
                'documentType' => '',
                'documentCountry' => '',
                'documentState' => '',
            ], 'Only accept specified type of document from specific countries and/or states.'),
        ]);
    }
}
