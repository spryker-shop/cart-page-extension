<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\TokenParser;

use SprykerShop\Yves\ShopUi\Twig\Node\ShopUiDefineTwigNode;
use Twig_Token;
use Twig_TokenParser;

class ShopUiDefineTwigTokenParser extends Twig_TokenParser
{
    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'define';
    }

    /**
     * @param \Twig_Token $token
     *
     * @return \ShopUiDefineTwigNode
     */
    public function parse(Twig_Token $token): ShopUiDefineTwigNode
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
        $value = $parser->getExpressionParser()->parseExpression();
        $line = $token->getLine();
        $tag = $this->getTag();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new ShopUiDefineTwigNode($name, $value, $line, $tag);
    }
}
