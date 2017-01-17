# meizu/yii2-admin

��ʼ����Ŀǰǿ�ҽ��������Ķ�һ��Yii2�Ĺٷ��ĵ���http://www.yiiframework.com/doc-2.0/guide-README.html

## �½���Ŀ
���Ը��ƴ���Ŀ������һ������Ŀ�����ƴ����ִ�����²��贴������ʼ�����ݼ��ɣ�
```
php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
php yii migrate --migrationPath=@yii/log/migrations --interactive=0
php yii migrate --migrationPath=@backend/migrations --interactive=0
```

�������������������񣨵�ȻҲ����ʹ�� nginx ���� apache�����ĵ��������ܣ���
```
php yii serve -t=@frontend/web -p=4001 127.0.0.1
php yii serve -t=@backend/web -p=4002 127.0.0.1
```

������Ŀǰ̨��http://127.0.0.1:4001/
������Ŀ��̨��http://127.0.0.1:4002/

http basic auth
�ʺţ�meizu
���룺meizu

��������Ա
�ʺţ�admin@meizu.com
���룺asdfqwWERxx

## Ŀ¼����&����淶
backend Ϊ��̨���
common ���ǰ��̨�Լ�����̨����ͨ�õĶ���
console ����̨����CLI�����ƻ�����Ľű�Ӧ��д�����Ŀ¼����
frontend Ϊǰ̨���
resource �����Ŀ��ص��ĵ��ȷǴ�������
yii ����̨���������ļ�
yii.bat ��� Windows ϵͳ�Ŀ���̨���������ļ�
init ��ʼ�������ļ��Ľű�
init.bat ��� Windows ϵͳ�ĳ�ʼ�������ļ��Ľű�
environments ��Ŵ��ڲ�ͬ�����������ļ����� init ��ʼ�����õ�ʱ��ʹ��
tests ��Ų�����صĴ��������ļ�
Vagrantfile vagrant �����ļ�
vagrant ��� vagrant ����ļ�
vendor Ϊ composer �Զ����ɵ�����Ŀ¼��������ĸ�Ŀ¼���κ��ļ�
composer.json composer ��ص��ļ�������
composer.lock composer ��ص��ļ�������
requirements.php ִ�и��ļ����Լ��ϵͳ PHP �����Ƿ��������� Yii2

�����ճ�������˵������ֻ���ע backend��ǰ̨����frontend����̨����console�������У��Լ� common ���ĸ�Ŀ¼��backend��ǰ̨����frontend����̨����console�������У��������������������ĳ��������Ŀֻ��Ҫ���к�̨��������ôֻ�� backend Ŀ¼�½��д����д���У��������ͬʱ�����������ͬ���� Model ��backend �� frontend ����Ҫ������ô���Խ���������� common Ŀ¼�¡�

backend,frontend,console ����Ŀ¼���ļ��ṹ��һ�µģ���ҪĿ¼�������£�
assets ǰ�˾�̬��Դ��ص�Ŀ¼
controllers ������Ŀ¼
models ģ��Ŀ¼
views ��ͼĿ¼
web index.php ����ڴˣ�Ϊ webroot ���Ŀ¼
runtime ����ʱ�ļ��У���� Yii2 ���й��������ɵ��ļ�

### �淶
���������ϲ�һ�������ã����� DB��Redis ������ã�ͳһ������ main-local.php ����
�� frontend��ǰ̨��Ϊ�����Ķ� frontend/web/index.php ���Կ���
```
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);
```
����ĻḲ�������ͬ�����á�
����ǹ����������� common/config �½������ã�frontend(ǰ̨�����е����÷��� frontend/config Ŀ¼�¡�

## Q&A

#### http basic auth ��֤��ش�����������ʵ�ֵģ�

���� \backend\controllers\BaseController::init() ����ʵ�ֵ��߼�������ʵ��ԭ����Ǽ����� EVENT_BEFORE_ACTION ���¼�������ִ�� Action ֮ǰ��� http basic auth��

#### Ȩ��У������������ģ�

\backend\controllers\BaseController::init() ����ݵ�ǰ Controller �� needPermission ����ֵȷ���Ƿ� attach ��Ӧ Behavior (\backend\components\AccessControl)��\backend\components\AccessControl ����ԭ����Ǽ����������� EVENT_BEFORE_ACTION ����ִ�� Action ֮ǰ�ᴥ�����¼��� ���жϵ�ǰ��¼�û��Ƿ���Ȩ�޷��ʶ�Ӧ�� Action ��

#### �������ĳЩ·�ɲ���Ȩ�޹��������û������Է��ʣ���

�����ַ�������һ���� Controller �̳� BaseController ��ʱ��needPermission ����Ϊ false����ô�� Controller ���µ����� Action ��Ĭ�ϲ���Ȩ��У�����ơ��ڶ��������� params.php ��������ļ��������磬/backend/config/params.php��
\backend\components\AccessControl ����� mz\admin\Helper::checkRoute ����û��Ƿ����ĳ·�ɵ�Ȩ�ޣ��鿴�÷����ľ���ʵ�ִ�����Կ������ȡ Yii::$app->params ������������ 'mz.admin.allowActions' ��ֵ�������ǰ����·��������������棬�������û������Է��ʡ�

#### ���� Model->save() ������ȴû�гɹ����浽���ݿ⣿

�����ֿ��ܣ���һ�֣���鿴 log �Ƿ���� Failed to set unsafe attribute �Ĵ��󣬳��������������Ϊ�� Model �� rules ����ʹ�ö�Ӧ�� attribute ���� safe����ô���� Model->load �Ͳ������ø� attribute ��ֵ��save() ��ʱ����Ȼ��û�б������ֵ�ˡ�
�ڶ��֣���鿴 log �Ƿ���� Model not inserted due to validation error �� Model not updated due to validation error ���󣬳��������������Ϊ��save() ��ʱ�򣬴��� attribute û��ͨ�� rules �����ĳЩ������֤��
Yii2�ٷ��ĵ���http://www.yiiframework.com/doc-2.0/guide-structure-models.html#validation-rules

#### ĳĳ�߼���������ʵ�ֵ�

���ʱ��Yii2�Ŀ���������ֻ������
```
$model->load(Yii::$app->request->post()) && $model->save()
```
�ļ򵥴��룬��û�о����ҵ��ʵ�֡���鿴��Ӧ Model �� beforeValidate��beforeSave��save��afterSave �����������������ҵ���߼��������⼸����������ʵ�ֵġ�

#### �ҵ�ĳһҵ���߼�Ӧ��д�����

��������һ��ܸ��ӵ��߼�����Ƶ�������� Model �����ʱ������Ե���дһ����ȥ������Щ�߼�����������һ����Ϸ���ҿ��Դ��� app\service\GameService �࣬����ʵ�־�����Ϸҵ���߼���
��Ȼ����ܲ�û���޶���Ѵ���д�����ʵ����ֻ�ܱ����ʵ���д�����ﶼ�ǿ��Եġ���һ�����ǻ����ʵ������������ĳ�߼�ֻ�뵥����¼��أ���������ȫ����ֱ��д�� Model ���档��� Model A �ڲ���֮����Ҫ����һ�� Model B ������������������ǿ����� Model A �� afterSave ����ȥ���д���
һ����˵��Controller�������������澡����д��дҵ���߼����߼���д�� Model ���������� Class���ࣩ���棬��������������ô���һ�Ƿ�����븴�ã����Ƿ��������ԡ�

#### ��̨��� ajax Post ����
mz.js �����װ��һ������ mz.post
���� mz.post(url, {'k':'v'}) �᷵��һ�� jqXhr �Ķ���Ҳ����ʡ�Ե�һ��������Ĭ���ǵ�ǰҳ��URL��ʾ������
```
mz.post(url, {'k':'v'])
    .done(function(body){
        if (json.code == 200) {
            alert('�����ɹ�');
        } else {
            alert('����ʧ�ܣ�'+json.message);
        }
    })
```

��Ӧ����� PHP ���������ôд
```
Yii::$app->response->format = 'json'; // ˵������ json
return [
    'code' => 100,
    'message' => '��Ϊ��̫˧������ܾ�ִ��',
];
```

#### GridView ��������������Զ���İ�ť

��ο����д����д�����ɣ����������Ķ� GridView ��Դ�룬������ע���Լ�ʾ�����롣

#### ��̨֧����Щ����

��̨֧�ֵ����ö��� backend/config/params.php �г����ˣ����ޱ�Ҫ�����ý����޸ġ�

#### ����˵���ĵ�

����û�ӵ�� /user ��Ȩ�ޣ���ôͬʱҲӵ�� /user/index , /user/create ��Ȩ��
