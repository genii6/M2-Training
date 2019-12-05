<?php


namespace Training\UrlRewriteCommand\Console\Command;


use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RewriteCommand extends Command
{
    const TYPE_ARG = "type";

    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        UrlRewriteFactory $urlRewriteFactory,
        StoreManagerInterface $storeManager,
        string $name = null)
    {
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName("training:url:rewrite");
        $this->setDescription("URL Rewrite Command");
        $this->addArgument(SELF::TYPE_ARG, InputArgument::REQUIRED, "Controller Type");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument(SELF::TYPE_ARG);
        $urlRewrite = $this->urlRewriteFactory->create();
        $storeId = $this->storeManager->getStore()->getId();

        $map = [
            "index" => 1,
            "json" => 2,
            "raw" => 3,
            "redirect" => 4,
            "forward" => 5
        ];

        $urlRewrite->setEntityType("custom")
            ->setEntityId($map[$type])
            ->setIsAutogenerated(1)
            ->setRequestPath("custom-page-" . $type . ".html")
            ->setTargetPath("training/index/" . $type)
            ->setStoreId($storeId)
            ->setRedirectType(0)
            ->save();
    }
}