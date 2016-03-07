<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dsanchez98/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dsanchez98\user;

use Yii;
use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @var array Model's map */
    private $_modelMap = [
        'User'             => 'dsanchez98\user\models\User',
        'Account'          => 'dsanchez98\user\models\Account',
        'Profile'          => 'dsanchez98\user\models\Profile',
        'Token'            => 'dsanchez98\user\models\Token',
        'RegistrationForm' => 'dsanchez98\user\models\RegistrationForm',
        'ResendForm'       => 'dsanchez98\user\models\ResendForm',
        'LoginForm'        => 'dsanchez98\user\models\LoginForm',
        'SettingsForm'     => 'dsanchez98\user\models\SettingsForm',
        'RecoveryForm'     => 'dsanchez98\user\models\RecoveryForm',
        'UserSearch'       => 'dsanchez98\user\models\UserSearch',
    ];

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "dsanchez98\\user\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
                if (in_array($name, ['User', 'Profile', 'Token', 'Account'])) {
                    Yii::$container->set($name . 'Query', function () use ($modelName) {
                        return $modelName::find();
                    });
                }
            }
            Yii::$container->setSingleton(Finder::className(), [
                'userQuery'    => Yii::$container->get('UserQuery'),
                'profileQuery' => Yii::$container->get('ProfileQuery'),
                'tokenQuery'   => Yii::$container->get('TokenQuery'),
                'accountQuery' => Yii::$container->get('AccountQuery'),
            ]);

            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'dsanchez98\user\commands';
            } else {
                Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl'        => ['/user/security/login'],
                    'identityClass'   => $module->modelMap['User'],
                ]);

                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules'  => $module->urlRules,
                ];

                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }

                $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);

                if (!$app->has('authClientCollection')) {
                    $app->set('authClientCollection', [
                        'class' => Collection::className(),
                    ]);
                }
            }

            if (!isset($app->get('i18n')->translations['user*'])) {
                $app->get('i18n')->translations['user*'] = [
                    'class'    => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }

            Yii::$container->set('dsanchez98\user\Mailer', $module->mailer);
        }
    }
}
