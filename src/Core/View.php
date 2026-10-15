<?php

namespace App\Core;

use Smarty\Smarty;
use App\Support\Text;

final class View
{
    private readonly Smarty $smarty;

    public function __construct(string $templateDir, string $varDir)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templateDir);
        $this->smarty->setCompileDir($varDir . '/compile');
        $this->smarty->setCacheDir($varDir . '/cache');
        $this->smarty->setEscapeHtml(true);
        $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIER, 'paragraphs', [Text::class, 'paragraphs']);
    }

    public function render(string $template, array $data = [])
    {
        $tpl = $this->smarty->createTemplate($template);
        $tpl->assign($data);

        return $tpl->fetch();
    }
}
