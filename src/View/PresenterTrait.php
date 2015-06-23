<?php
namespace Cake\View;

use Cake\Core\App;
use Cake\Datasource\EntityInterface;
use Cake\View\View;

trait PresenterTrait
{
    /**
     * Generates the presenter object.
     *
     * @param  \Cake\Datasource\EntityInterface $entity Entity to create the object for.
     * @param  null|string $class Class to use for the presenter.
     * @return \Cake\View\Presenter
     */
    public function getPresenter(EntityInterface $entity, $class = null)
    {
        if ($class === null) {
            list($plugin, $class) = $this->pluginSplit(get_class($entity));
            list(, $class) = namespaceSplit($class);
            
            if ($plugin) {
                $class = $plugin . '.' . $class;
            }
        }
        
        $class = App::className($class, 'View/Presenter', 'Presenter');
        
        if ($class === false) {
            $class = 'Cake\View\Presenter';
        }
        
        return new $class($entity, $this);
    }
}
