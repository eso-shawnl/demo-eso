<?php
class ModelModelsInterface extends Model {

    /**
     * @param $user
     * @param $class_name
     * @param $data_type
     * @param $action
     * @param $data
     * @return array|mixed|string
     * 接下来反射它，只要把类名"Person"传递给ReflectionClass就可以了：

    $class = new ReflectionClass('Person'); // 建立 Person这个类的反射类

    $instance  = $class->newInstanceArgs($args); // 相当于实例化Person 类

    1）获取属性(Properties)：
    $properties = $class->getProperties();
    foreach ($properties as $property) {
    echo $property->getName() . "\n";
    }
    // 输出/;
    // _allowDynamicAttributes
    // id
    // name
    // biography
    默认情况下，ReflectionClass会获取到所有的属性，private 和 protected的也可以。如果只想获取到private属性，就要额外传个参数：
    $private_properties = $class->getProperties(ReflectionProperty::IS_PRIVATE);
    可用参数列表：
    ReflectionProperty::IS_STATIC
    ReflectionProperty::IS_PUBLIC
    ReflectionProperty::IS_PROTECTED
    ReflectionProperty::IS_PRIVATE
    通过$property->getName()可以得到属性名。

    2）获取注释：
    通过getDocComment可以得到写给property的注释。
    foreach ($properties as $property) {
    if ($property->isProtected()) {
    $docblock = $property->getDocComment();
    preg_match('/ type\=([a-z_]*) /', $property->getDocComment(), $matches);
    echo $matches[1] . "\n";
    }
    }
    // Output:
    // primary_autoincrement
    //varchar
    // text

    3）获取类的方法
    getMethods()       来获取到类的所有methods。
    hasMethod(string)  是否存在某个方法
    getMethod(string)  获取方法

    4）执行类的方法：
    $instance->getName(); // 执行Person 里的方法getName
    // 或者：
    $method = $class->getmethod('getName');  // 获取Person 类中的getName方法
    $method->invoke($instance);              // 执行getName 方法
    // 或者：
    $method = $class->getmethod('setName');  // 获取Person 类中的setName方法
    $method->invokeArgs($instance, array('snsgou.com'));

    二、通过ReflectionMethod，我们可以得到Person类的某个方法的信息：
    是否“public”、“protected”、“private” 、“static”类型
    方法的参数列表
    方法的参数个数
    反调用类的方法
    view source?
    // 执行detail方法
    $method = new ReflectionMethod('Person', 'test');
    if ($method->isPublic() && !$method->isStatic()) {
    echo 'Action is right';
    }
    echo $method->getNumberOfParameters(); // 参数个数
    echo $method->getParameters(); // 参数对象数组

     */
    public function model_interface($user ,$class_name , $data_type, $action, $data) {

        if(!empty($class_name)){
            //print_r(get_class_methods('ModelModelsInterface'));

            return $this->model_control($user ,$class_name , $data_type, $action, $data);
        }
        else{
            return array();
        }

    }

    public function multiple_interface($mutiple_data = array(0)) {

        if(is_array($mutiple_data) && !empty($mutiple_data)){

            $result = array();

            foreach($mutiple_data as $key => $value){
                $result[$key] = $this->model_control($value['user'] ,$value['class_name'] , $value['data_type'], $value['action'], $value['data']);
            }

        }
        else{
            return array();
        }

    }

    private function model_control($user ,$class_name , $data_type, $action, $data) {

        $class_array = array('ticket'=>'Ticket',
                            'order'=>'Order',
                            'event'=>'Event',
                            'configuration'=>'Configuration',
                            'card'=>'Card');

        if(file_exists(DIR_APPLICATION.'model/models/'.$class_name.'.php')){

            require_once(DIR_APPLICATION.'model/models/'.$class_name.'.php');

            $class = new ReflectionClass($class_array[$class_name]); // 建立 Person这个类的反射类

            $method_name = $action.'_'.$class_name.'_'. $data_type;

            if($class -> hasMethod($method_name)){

                $instance  = $class->newInstance($this->registry);

                $method = $class->getmethod($method_name);  // 获取Person 类中的getName方法

                return $method->invoke($instance,$user,$data); ;
            }

        }
        else{
            return array('error'=> 'This class is not exist. '.$class_name);
        }

    }


}
