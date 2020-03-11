# Changelog

All notable changes to `nova-filemanager` will be documented in this file

## v2.7 - 2020-03-11

#### Improvements
* Nova 3 Support

#### Fixes
* Namespace PSR-4 

## v2.6.6 - 2020-02-24

#### Improvements
* Working with Nova Flexible Content

#### Fixes
* Fix Layout #119 
* Filter fix for Nova Flexible Content


## v2.6.5 - 2019-12-17

#### Fixes
* Fix Detail Popup: Delete and rename buttons now working
* Fix detach popup
* Fix uploading a folder inside a folder #108 (Thanks to @victorlap)

## v2.6 - 2019-11-18

#### Improvements
* Rounded and Squared methods to customize the search and index previews.

#### Fixes
* Fix for global search


## v2.5 - 2019-11-04

#### Improvements
* Upload validation rules added
* Multi select deletion (Thanks to @godkinmo)
* New buttons options to hide buttons on tool or field

#### Fixes

* Fix nova 2.5 modals. Now all modals open in a custom portal.
* Events properties are now public


## v2.3 - 2019-07-24

#### Improvements

* Folder upload
* New events added for file and folders.
* New jobs config option to Post Processing.
* Delete and rename buttons in grid and list views.
* Images previews now uses [v-viewer](https://github.com/mirari/v-viewer).
* Possibility to exclude Folders using `.hide` file (#65)

#### Fixes

* File cannot be uploaded when navigated back to root directory (#56)

### v2.2 - 2019-05-03

First release for Nova v2

#### Improvements

 * Search image: FilemanagerField now implements Cover contract. So now when you search your resources it will show like Avatar field


### v2.1.3 - 2019-05-03

Release for Nova v1

### v2.1.2 - 2019-04-30

New way to move files between folders with drag and drop

### v2.1 - 2019-04-02

Updated documentation with vuepress.

### v2.1 - 2019-03-19

#### Improvements

* Change view button. Grid or List
* Rename method. You can now rename your files
* Detail and new folder modals close on click outside

#### Fixes

* Upload progress fix when name is very long


### v2.0 - 2019-03-11

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


-----



### v1.1.1 - 2018-10-09

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

### v1.1.0 - 2018-09-21

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

### v1.0.1 - 2018-08-29

Beta release for some tests

-----

### v1.0.0 - 2018-08-30 

Filemanager ready for Production with version 1.0.0

