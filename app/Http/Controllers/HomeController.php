<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\Problem;
use App\Utils\HumClient\Clients;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $nodes = Node::get();
        $problems = Problem::get();
        $vcpus = [];
        $memoryBytes = [];
        $clients = new Clients(config("humstack.apiServerURL", "http://localhost:8080"));
        foreach ($problems as $problem) {
            $group = $problem->group->name;
            foreach ($problem->deployedTeams as $team) {
                $ns = $problem->name;
                $vmList = $clients->VirtualMachine()->list($group, $ns);

                foreach ($vmList->data as $vm) {
                    $nodeName = $vm->meta->annotations['virtualmachinev0/node_name'];
                    $vcpus[$nodeName] = ($vcpus[$nodeName] ?? 0) + (int)$vm->spec->requestVcpus;
                    $memoryBytes[$nodeName] = ($memoryBytes[$nodeName] ?? 0) + $this->withUnitToWithoutUnit($vm->spec->requestMemory);
                }
            }
        }

        return view('dashboard', [
            'nodes' => $nodes,
            'vcpus' => $vcpus,
            'memoryBytes' => $memoryBytes,
        ]);
    }

    private function withUnitToWithoutUnit($withUnit)
    {
        $length = strlen($withUnit);
        if ($length == 0) {
            return 0;
        }
        if ($withUnit[$length-1] >= '0' && $withUnit[$length-1] <='9') {
            return (int)$withUnit;
        }

        $withoutUnit = (int)substr($withUnit, 0, -1);
        switch ($withUnit[$length-1]) {
            case 'G':
                return $withoutUnit * 1024 * 1024 * 1024;
            case 'M':
                return $withoutUnit * 1024 * 1024;
            case 'K':
                return $withoutUnit * 1024;
        }
        return 0;
    }
}
