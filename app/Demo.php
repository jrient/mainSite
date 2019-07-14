<?php
/**
 * User: jrient
 * Date: 2018/12/24
 * Time: 22:42
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    /*
     * $model = self::firstOrCreate(['a' => 1]); 查找a=1的数据，没有则新增，返回一个model实例
     * $model = self::firstOrNew(['a' => 1]); 查找a=1的数据，没有则返回一个model实例
     *
     * $num = self::where(['id' => 1])->update(['a' => 1]);
     * $num = self::destroy(1); 通过主键删除
     * $num = self::where(['id' => 1])->delete();
     *
     * */
    //指定表名
    protected $table = 'demo';
    //指定主键
    protected $primaryKey = 'id';
    //允许批量赋值的字段
    protected $fillable = ['a', 'b'];
    //不允许批量赋值的字段
    protected $guarded = ['c', 'd'];
    //是否使用默认维护create_at update_at
    public $timestamps = true;

    //设置默认 create_at update_at 的数据格式
    public function getDateFormat()
    {
        return time();
    }

    //设置模型中时间类型数据的返回格式
    public function asDateTime($value)
    {
        return $value;
    }

    static public function hello()
    {
        echo 'hello';
    }
}