FileCache
=========

设置或获取文件缓存

案例
----

### 设置和获取文件缓存
```php
// 设置缓存
widget()->fileCache('key', 'value');
// true

// 获取缓存
widget()->fileCache('key');
// value
```

更多案例请查看"[缓存](cache.md#案例)"章节

调用方式
--------

### 选项

名称      | 类型   | 默认值    | 说明
----------|--------|-----------|------
dir       | string | cache     | 缓存文件存储的目录

### 继承的方法

通用方法请查看[cache](cache.md#通用方法)微件文档

### 方法

#### fileCache->getDir()
获取文件缓存的目录

#### fileCache->setDir($dir)
设置文件缓存目录

#### fileCache->getFile($key)
根据缓存名称获取缓存文件路径