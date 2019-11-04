# Customization

## Naming strategy

You can resolve the upload file name creating your own class and setting in `config/filemanager.php`

These class should extends `Infinety\Filemanager\Http\Services\AbstractNamingStrategy`.


Default class get the filename of uploaded file and check if exists. If exists add a random text of 7 characters.

You can create your custom class to customize the uploaded name.


```php
namespace Infinety\Filemanager\Http\Services;

use Illuminate\Http\UploadedFile;

class DefaultNamingStrategy extends AbstractNamingStrategy
{
    public function name(string $currentFolder, UploadedFile $file) : string
    {
        $filename = $file->getClientOriginalName();

        while ($this->storage->has($currentFolder.'/'.$filename)) {
            $filename = sprintf(
                '%s_%s.%s',
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                str_random(7),
                $file->getClientOriginalExtension()
            );
        }

        return $filename;
    }
}
```

## Localization

Set your translations in the corresponding xx.json file located in /resources/lang/vendor/nova.

For example to translate this tool to spanish, you can use this json.

```json
"Filemanager": "Gestor de archivos",
"Create folder": "Crear carpeta",
"Write a folder name": "Escribe el nombre de la carpeta",
"Create": "Crear",
"Creating": "Creando",
"Cancel": "Cancelar",
"The folder name is required": "El nombre de la carpeta es requerido",
"Folder created successfully": "Carpeta creada correctamente",
"Error creating the folder": "Error al crear la carpeta",
"Preview of": "Vista previa de",
"Name": "Nombre",
"Mime Type": "Tipo de archivo",
"Last Modification": "Última modificación",
"Size": "Tamaño",
"Dimensions": "Dimensiones",
"Url": "Enlace",
"Copy": "Copiar",
"Select file": "Seleccionar archivo",
"Remove File": "Eliminar archivo",
"Are you sure?": "¿Estás seguro?",
"Removing...": "Eliminando...",
"Text copied to clipboard": "Texto copiado al portapapeles",
"File removed successfully": "Archivo eliminado correctamente",
"Error removing the file. Please check permissions": "Error al eliminar el archivo. Por favor, comprueba los permisos",
"Home": "Inicio",
"You don\\'t have permissions to view this folder": "No tienes permisos para ver esta carpeta",
"No files or folders in current directory": "Esta carpeta esta vacía",
"Remove directory": "Eliminar carpeta",
"Folder removed successfully": "Carpeta eliminada correctamente",
"Error removing the folder. Please check permissions": "Error al eliminar la carpeta. Por favor, comprueba los permisos",
"Upload": "Subir",
"Error on upload": "Error al subir",
"Success": "Éxito",
"Error uploading the file. Check your MaxFilesize or permissions": "Error al subir el archivo. Comprueba la directiva MaxFilesize o los permisos",
"Select a file": "Selecciona un archivo",
"Open FileManager": "Abrir Gestor de archivos",
"This image": "Esta imagen",
"could not be found.": "no se encuentra.",
"Delete": "Eliminar",
"Deleting": "Eliminando",
"Deselect File": "Deseleccionar archivo",
"Are you sure you want to deselect this file?": "¿Estas seguro de querer deseleccionar este archivo?",
"Remember: The file will not be delete from your storage": "Recuerda: El archivo no será eliminado de tu disco",
"Deselect": "Deseleccionar",
"Only files below 350 Kb will be shown": "Solo se mostraran archivos con un peso menor a 350 Kb",
"Filter by ...": "Filtrar por ...",
"Go up": "Arriba",
"Rename folder": "Renombrar carpeta",
"Rename file": "Renombrar archivo",
"Rename": "Renombrar",
"Remove folder": "Eliminar carpeta",
"Remove file": "Eliminar archivo",
"Are you sure you want to remove this folder?": "¿Estás seguro de querer eliminar esta carpeta?",
"Remember: The folder and all his contents will be delete from your storage": "Recuerda: La carpeta y todos sus contenidos seran eliminados por completo",
"Are you sure you want to remove this file?": "¿Estás seguro de querer eliminar este archivo?",
"Remember: The file will be delete from your storage": "Recuerda: El archivo será eliminado completamente",
"Uploading Folder": "Subiendo carpeta",
"Deleted successfully": "Eliminado correctamente",
"Renamed successfully": "Renombrado correctamente",
"Remove selected?": "¿Elminar seleccionados?",
"Are you sure you want to remove selected files or folders?": "Estás seguro de querer eliminar los archivos o carpetas seleccionadas?",
"Remember: The file and folder and all his contents will be delete from your storage": "Recuerda: Los archivos/carpetas y todos sus contenidos serán eliminadas",
"Error deleting. Please, see your logs": "Error al eliminar. Por favor, comprueba tus logs"
```




