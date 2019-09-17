<?php
namespace ISN\CompanyExt\Api;

use ISN\CompanyExt\Api\Data\CompanyISNInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CompanyISNRepositoryInterface {
    public function save(CompanyISNInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(CompanyISNInterface $page);

    public function deleteById($id);
}