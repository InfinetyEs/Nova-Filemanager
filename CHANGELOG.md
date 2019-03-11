# Changelog

All notable changes to `nova-filemanager` will be documented in this file

## 2.0 - 2019-03-11

New config file and new options for tool and field to search and filter

#### Improvements

* New search option in tool and field modal https://github.com/InfinetyES/Nova-Filemanager/issues/34
* New filters. You can create your own filters. You can set a default filter ny config or by field.
* Visibility when upload. You can set the visibility of uploaded files now. https://github.com/InfinetyES/Nova-Filemanager/issues/26
* New option to set cache by file. This is great for large folders or cloud providers
* `displayAsImage` field option will show the preview also when editing.

#### Fixes

* File url preview fixed. This should fix https://github.com/InfinetyES/Nova-Filemanager/issues/19 https://github.com/InfinetyES/Nova-Filemanager/issues/23 https://github.com/InfinetyES/Nova-Filemanager/issues/36
* When a folder is specified, Home will be the folder. https://github.com/InfinetyES/Nova-Filemanager/issues/28
* SQL now shows the code in preview popup. FIles above 350 KB will not show in code editor.
* SVG preview image fixed

#### Tips
* Json localization strings updated


## 1.1.1 - 2018-10-09

Field new option folder | Remove value from field

#### Improvements

* New folder option in the field to set a custom init folder https://github.com/InfinetyES/Nova-Filemanager/issues/21
* New button to remove (deselect) a file from a field. https://github.com/InfinetyES/Nova-Filemanager/issues/16

#### Fixes

* Unused functions removed
* Cloud disk working: `s3`, `s3-cached` and `google`.
* Detail view when no file is selected

#### Tips
* Json localization strings updated

-----

## 1.1.0 - 2018-09-21

Major update v1

#### Improvements

* New upload method (Drag&Drop into file manager)
* AWS s3 support (No image dimensions) https://github.com/InfinetyES/Nova-Filemanager/issues/6
* Preview of more file types:
	* PDF (native iframe, object)
	* Text
	* Zip
	* Audio
	* Videos
* Translations added (json) https://github.com/InfinetyES/Nova-Filemanager/issues/10


#### Fixes

* Slash URL working https://github.com/InfinetyES/Nova-Filemanager/issues/7
* Upload image JS Object https://github.com/InfinetyES/Nova-Filemanager/issues/8
* Label error on editing https://github.com/InfinetyES/Nova-Filemanager/issues/12
* UTF-8 characters https://github.com/InfinetyES/Nova-Filemanager/issues/13
* Other fixes

-----

## 0.0.1 - 2018-08-29

Beta release for some tests

-----

## 1.0.0 - 2018-08-30 

Filemanager ready for Production with version 1.0.0

