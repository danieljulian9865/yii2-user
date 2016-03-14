<?php
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dsanchez98\user\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                   $this
 * @var dsanchez98\user\models\LoginForm $model
 * @var dsanchez98\user\Module           $module
 */
$this->title                   = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<!--<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
<?php
$form                          = ActiveForm::begin([
            'id'                     => 'login-form',
            'enableAjaxValidation'   => true,
            'enableClientValidation' => false,
            'validateOnBlur'         => false,
            'validateOnType'         => false,
            'validateOnChange'       => false,
        ])
?>

<?=
$form->field($model, 'login',
             ['inputOptions' => ['autofocus' => 'autofocus',
        'class'     => 'form-control', 'tabindex'  => '1']])
?>

<?=
$form->field($model, 'password',
             ['inputOptions' => ['class'    => 'form-control',
        'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ($module->enablePasswordRecovery ? ' (' . Html::a(Yii::t('user',
                                                                                                                                           'Forgot password?'),
                                                                                                                                           ['/user/recovery/request'],
                                                                                                                                           ['tabindex' => '5']) . ')' : ''))
?>

<?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

<?=
Html::submitButton(Yii::t('user', 'Sign in'),
                          ['class'    => 'btn btn-primary btn-block',
    'tabindex' => '3'])
?>

<?php ActiveForm::end(); ?>
            </div>
        </div>
<?php if ($module->enableConfirmation): ?>
                                            <p class="text-center">
    <?=
    Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'),
                   ['/user/registration/resend'])
    ?>
                                            </p>
<?php endif ?>
<?php if ($module->enableRegistration): ?>
                                            <p class="text-center">
    <?=
    Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'),
                   ['/user/registration/register'])
    ?>
                                            </p>
<?php endif ?>
<?=
Connect::widget([
    'baseAuthUrl' => ['/user/security/auth'],
])
?>
    </div>
</div>-->



<div class="site-login">
    <?php
    $form = ActiveForm::begin([
                'id'          => 'login-form',
                'options'     => ['class' => 'form-horizontal form-signin'],
                'fieldConfig' => [
                    'template'     => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
    ]);
    ?>


    <?=
    Html::tag('h2', 'Iniciar sesión', ['class' => 'form-signin-heading'])
    ?>

    <div class="login-wrap">
        <?=
        $form->field($model, 'login')->textInput(['autofocus'   => true,
            'placeholder' => 'Usuario'])
        ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Contraseña']) ?>

        <?php
//        $form->field($model, 'rememberMe')->checkbox([
//            'template' => "<div class=\"col-lg-offset-1 col-lg-10\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
//        ])
        ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-10">
                <?=
                Html::submitButton('Iniciar',
                                   ['class' => 'btn btn-lg btn-login btn-block',
                    'name'  => 'login-button'])
                ?>
            </div>
        </div>


        <?php if ($module->enablePasswordRecovery): ?>

            <p class="text-center">
                <?=
                ' (' . Html::a(Yii::t('user', 'Forgot password?'),
                                      ['/user/recovery/request'],
                                      ['tabindex' => '5']) . ')'
                ?>
            </p>

        <?php endif ?>




        <?php if ($module->enableConfirmation): ?>
            <p class="text-center">
                <?=
                Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'),
                               ['/user/registration/resend'])
                ?>
            </p>
        <?php endif ?>
        <?php if ($module->enableRegistration): ?>
            <p class="text-center">
                <?=
                Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'),
                               ['/user/registration/register'])
                ?>
            </p>
        <?php endif ?>
        <?=
        Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ])
        ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>

