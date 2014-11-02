<?php
class CoreUserIdentity extends CUserIdentity
{
  private $id;

	public function authenticate($admin = false)
	{
	  if (empty($this->username) || !($client = CoreClient::model()->with('admin')->find('t.login=:login'.($admin?' AND admin.client_id=t.id':''), array(':login'=>$this->username))))
	    $this->errorCode=self::ERROR_USERNAME_INVALID;
    elseif (md5($this->password) !== $client->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
      if (empty($client->token))
      {
		    $client->token = md5($client->id.$client->login).md5($client->login);
		    $client->save(false);
      }

      $unitAccess = array();
      if ($admin && $client->admin)
      {
        $rights = array();
        if (!is_null($client->admin->group_id))
          $rights = CoreAdminGroupRight::model()->with('unit.group')->findAll(array(
            'condition'=>'t.admin_group_id='.$client->admin->group_id,//:admin_group_id',
            'order'=>'group.sort, group.name, unit.sort, unit.name',
          ), array(':admin_group_id'=>$client->admin->group_id));

        $this->setState('rights', $rights);
      }

		  $this->id = $client->id;
		  $this->setState('login', $client->login);
      $this->setState('email', $client->email);
		  $this->setState('token', $client->token);
      $this->setState('admin', ($admin && $client->admin));
      $this->setState('root', ($admin && $client->admin && $client->admin->root));
			$this->errorCode=self::ERROR_NONE;
		}

    return !$this->errorCode;
	}

	public function getId()
	{
	  return $this->id;
	}
}