<?php

/* preview.twig */
class __TwigTemplate_83ef9bfea1cebf55a5eec9dcd75ce69ffd95d2724d0bb81a25600edd4f840f0b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"js-wpml-ls-preview-wrapper wpml-ls-preview-wrapper";
        if (($context["class"] ?? null)) {
            echo " ";
            echo twig_escape_filter($this->env, ($context["class"] ?? null), "html", null, true);
        }
        echo "\">
    <strong class=\"wpml-ls-preview-label\">";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["strings"] ?? null), "misc", array()), "label_preview", array()), "html", null, true);
        echo "</strong>
    <span class=\"spinner\"></span>
    <div class=\"js-wpml-ls-preview\">";
        // line 4
        echo $this->getAttribute(($context["preview"] ?? null), "html", array());
        echo "</div>
</div>";
    }

    public function getTemplateName()
    {
        return "preview.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  32 => 4,  27 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"js-wpml-ls-preview-wrapper wpml-ls-preview-wrapper{% if class %} {{ class }}{% endif %}\">
    <strong class=\"wpml-ls-preview-label\">{{ strings.misc.label_preview }}</strong>
    <span class=\"spinner\"></span>
    <div class=\"js-wpml-ls-preview\">{{ preview.html|raw }}</div>
</div>", "preview.twig", "D:\\wordpress\\toannang\\toannang-wp-wc-lag-v1.2.1\\wp-content\\plugins\\sitepress-multilingual-cms\\templates\\language-switcher-admin-ui\\preview.twig");
    }
}
