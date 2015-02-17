<?php

namespace SDIS62\Core\Ops\Entity\Service;

use Mockery;
use SDIS62\Core\Ops as Core;
use PHPUnit_Framework_TestCase;

class MaterielServiceTest extends PHPUnit_Framework_TestCase
{
    public function test_if_it_find()
    {
        // Init ..
        $repository_materiel = Mockery::mock('SDIS62\Core\Ops\Repository\MaterielRepositoryInterface')->makePartial();
        $repository_centre = Mockery::mock('SDIS62\Core\Ops\Repository\CentreRepositoryInterface')->makePartial();
        $service = new Core\Service\MaterielService($repository_materiel, $repository_centre);

        // Prepare ..
        $repository_materiel->shouldReceive('find')->with(1)->andReturn(true)->once();

        // Test!
        $this->assertTrue($service->find(1));
    }

    public function test_if_it_delete()
    {
        // Init ..
        $repository_materiel = Mockery::mock('SDIS62\Core\Ops\Repository\MaterielRepositoryInterface')->makePartial();
        $repository_centre = Mockery::mock('SDIS62\Core\Ops\Repository\CentreRepositoryInterface')->makePartial();
        $service = new Core\Service\MaterielService($repository_materiel, $repository_centre);

        // Prepare ..
        $materiel = Mockery::mock('SDIS62\Core\Ops\Entity\Materiel');
        $repository_materiel->shouldReceive('find')->with(1)->andReturn($materiel)->once();
        $repository_materiel->shouldReceive('find')->with(2)->andReturn(null)->once();
        $repository_materiel->shouldReceive('delete')->with($materiel)->once();

        // Test!
        $this->assertEquals($materiel, $service->delete(1));
        $this->assertNull($service->delete(2));
    }

    public function test_if_it_create()
    {
        // Init ..
        $repository_materiel = Mockery::mock('SDIS62\Core\Ops\Repository\MaterielRepositoryInterface')->makePartial();
        $repository_centre = Mockery::mock('SDIS62\Core\Ops\Repository\CentreRepositoryInterface')->makePartial();
        $service = new Core\Service\MaterielService($repository_materiel, $repository_centre);

        // Prepare ..
        $data = array('name' => 'VSAV1', 'actif' => true, 'centre' => 1);
        $centre = new Core\Entity\Centre('CIS Arras');
        $materiel_expected = new Core\Entity\Materiel($centre, 'VSAV1');
        $materiel_expected->setActif();
        $repository_centre->shouldReceive('find')->with(1)->andReturn($centre)->once();
        $repository_materiel->shouldReceive('save')->once();

        // Test!
        $this->assertEquals($materiel_expected, $service->save($data));
    }

    public function test_if_it_update()
    {
        // Init ..
        $repository_materiel = Mockery::mock('SDIS62\Core\Ops\Repository\MaterielRepositoryInterface')->makePartial();
        $repository_centre = Mockery::mock('SDIS62\Core\Ops\Repository\CentreRepositoryInterface')->makePartial();
        $service = new Core\Service\MaterielService($repository_materiel, $repository_centre);

        // Prepare ..
        $data = array('name' => 'VSAV2', 'actif' => false, 'centre' => 2);
        $centre1 = new Core\Entity\Centre('CIS Arras');
        $materiel_updated = new Core\Entity\Materiel($centre1, 'VSAV1');
        $materiel_updated->setActif();
        $centre2 = new Core\Entity\Centre('CIS Bethune');
        $materiel_expected = new Core\Entity\Materiel($centre2, 'VSAV2');
        $materiel_expected->setActif(false);
        $repository_centre->shouldReceive('find')->with(2)->andReturn($centre2)->once();
        $repository_centre->shouldReceive('find')->with(3)->andReturn(null)->once();
        $repository_materiel->shouldReceive('find')->with(15)->andReturn($materiel_updated)->once();
        $repository_materiel->shouldReceive('save')->once();

        // Test!
        $this->assertEquals($materiel_expected, $service->save($data, 15));
        $this->assertNull($service->save(array('centre' => 3), 15));
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}
