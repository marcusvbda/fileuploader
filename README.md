# Faça upload de arquivos na sua app laravel

Esta biblioteca é uma helper auxiliar para upload de arquivos, classificação do mesmo em categorias e relacionamento com outros models.

### Como devo usar ?
#####  Upload de arquivos
- procedimento de upload de arquivos
```
use marcusvbda\uploader\Controllers\UploaderController as Uploader;
use marcusvbda\uploader\Models\File as _File;
use marcusvbda\uploader\Models\FileCategory;
use Illuminate\Http\Request;

$data = $request->all();
//efetua o upload de arquivo
$file = Uploader::upload($data["_file"],$data["_name"],$data["_alt"]);
//cria uma thumbnail para o arquivo
Uploader::makeThumbnail($file);
```

- editando arquivo
```
use marcusvbda\uploader\Controllers\UploaderController as Uploader;

public function fileEdit(_File $file)
{
    $data = ["name" => "novo nome alterado","description"=>"nova descrição alterado"];
    //aqui editamos name e description, o metodo alterará automaticamente o nome do arquivo e suas urls
    $file = Uploader::edit($file,$data);
}
```

#####  Vincular arquivo a um model
- exemplo do model
```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use marcusvbda\uploader\Traits\HasFiles;

class Product extends Model
{
	use HasFiles;
    protected $table = 'products';
	protected $fillable = [
		'name'
	];
}
```
- criar e excluir categoria de arquivos
```
use marcusvbda\uploader\Models\FileCategory;

public function create(Request $request)
{
    $data = $request->all();
    //adiciona uma nova categoria
    $category = FileCategory::create($data);
}

public function delete(FileCategory $category)
{
    //exclusão simples
    $category->delete();
}

```

- vinculando e desvinculando ao model
```
use marcusvbda\uploader\Controllers\UploaderController as Uploader;
use marcusvbda\uploader\Models\File as _File;
use App\Models\Product;

public function addFile(Product $product,_File $file)
{
    //$product é uma instância do model PRODUCT, assim como $file é de _File
    //no caso abaixo vinculamos o arquivo ao produto
    $product->addFile($file);
}

public function removeFile(Product $product,_File $file)
{
    //esse metodo apenas desvincula o arquivo de $product, porém o arquivo se mantem na
    //biblioteca de importados
    $product->removeFile($file);
}
```
- acessar os arquivos vinculados ao model e as categorias
```
use marcusvbda\uploader\Models\File as _File;
use App\Models\Product;

public function getModelFiles(Product $product)
{
    $files = $product->files;
}

public function getCategoryFiles(FileCategory $category)
{
    $files = $category->files;
}

public function getFileCategory(_File $file)
{
    $file = $file->category;
}
```
- reordenar imagens do model
```
public function reorderFiles(Product $product)
{
     $order = [
        ["id"=>1,"ordination"=>2],
        ["id"=>2,"ordination"=>4],
        ["id"=>3,"ordination"=>1],
        ["id"=>4,"ordination"=>3]
    ];
    $product->reorderFiles($order);
}
```

### Instalação
##### Pacotes Requeridos
  - [cviebrock/eloquent-sluggable](https://github.com/cviebrock/eloquent-sluggable)
  - [spatie/laravel-image-optimizer](https://github.com/spatie/laravel-image-optimizer)

Instale as dependêcias e inicie o serve
```sh
$ composer require marcusvbda/uploader
```
adicione a config/app , em provider a linha abaixo
```
marcusvbda\uploader\UploaderServiceProvider::class
```
execute no dash
```
$ php artisan vendor:publish
$ php artisan migrate
```

### Configurações

No diretório config de seu projeto laravel, após a instalação completa você encontrará um arquivo chamado uploader.php, nele voçê poderá configurar as seguintes informações

| campo | Valor padrão | Descrição | 
| ------ | ------ |------ |
| image_server | [http://127.0.0.1:8000/files/get/][PlDb] | url onde pederá acessar os arquivo, no vaso http://127.0.0.1:8000/files/get/{slug-text}.extensão |
| upload_path | uploads | diretório no storage onde os arquivos serão salvos |
| thumbnail_path | uploads/thumbnail | diretório no storage onde os thumbnails dos arquivos serão salvos |
| thumbnail_height | 90 | altura do thumbnail, a largura é calculada proporcionalmente |
| cascadeFile | false | ao excluir uma categoria, se estiver true irá excluir também em cascata os arquivos vinculados a ela |