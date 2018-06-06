<?php
/*
 * 使用:
 * $page = new Page(连接符,查询语句,当前页码,每页大小,页码符)
 * 连接符:一个MYSQL连接标识符,如果该参数留空,则使用最近一个连接
 * 查询语句:SQL语句
 * 当前页码:指定当前是第几页
 * 每页大小:每页显示的记录数
 * 页码符:指定当前页面URL格式
 *
 * 使用例子:
 * $sql = "select * from aa";
 * $page = new Page($conn,$sql,$_GET['page'],4,"?page=");
 *
 * 获得当前页码
 * $page->page;
 *
 * 获得总页数
 * $page->pageCount;
 *
 * 获得总记录数
 * $page->rowCount;
 *
 * 获得本页记录数
 * $page->listSize;
 *
 * 获得记录集
 * $page->list;
 * 记录集是一个2维数组,例:list[0]['id']访问第一条记录的id字段值.
 *
 * 获得页码列表
 * $page->getPageList();
 */

class Page
{
  //基础数据
  var $sql;
  var $page;
  var $pageSize;
  var $pageStr;
  //统计数据
  var $pageCount; //页数
  var $rowCount; //记录数
  //结果数据
  var $list = array(); //结果行数组
  var $listSize ;
  //构造函数
  function Page($conn,$sql_in,$page_in,$pageSize_in,$pageStr_in)
  {
    $this->sql = $sql_in;
    $this->page = intval($page_in);
    $this->pageSize = $pageSize_in;
    $this->pageStr = $pageStr_in;
    //页码为空或小于1的处理
    if(!$this->page||$this->page<1)
    {
      $this->page = 1;
    }
    //查询总记录数
    $rowCountSql = preg_replace("/([\w\W]*?select)([\w\W]*?)(from[\w\W]*?)/i","$1 count(0) $3",$this->sql);
    if(!$conn)
      $rs = mysql_query($rowCountSql) or die("bnc.page: error on getting rowCount.");
    else
      $rs = mysql_query($rowCountSql,$conn) or die("bnc.page: error on getting rowCount.");
    $rowCountRow = mysql_fetch_row($rs);
    $this->rowCount=$rowCountRow[0];
    //计算总页数
    if($this->rowCount%$this->pageSize==0)
      $this->pageCount = intval($this->rowCount/$this->pageSize);
    else
      $this->pageCount = intval($this->rowCount/$this->pageSize)+1;
    //SQL偏移量
    $offset = ($this->page-1)*$this->pageSize;
    if(!$conn)
   $rs = mysql_query($this->sql." limit $offset,".$this->pageSize) or
   die("bnc.page: error on listing.");
    else
 $rs = mysql_query($this->sql." limit $offset,".$this->pageSize,$conn) or
 die("bnc.page: error on listing.");
    while($row=mysql_fetch_array($rs))
    {
      $this->list[]=$row;
    }
    $this->listSize = count($this->list);
  }
  /*
   * getPageList方法生成一个较简单的页码列表
   * 如果需要定制页码列表,可以修改这里的代码,或者使用总页数/总记录数等信息进行计算生成.
   */
  function getPageList()
  {
    $firstPage;
    $previousPage;
    $pageList;
    $nextPage;
    $lastPage;
    $currentPage;
    //如果页码>1则显示首页连接
    if($this->page>1)
    {
      $firstPage = "<a href=\"".$this->pageStr."1\">首页</a>";
    }
    //如果页码>1则显示上一页连接
    if($this->page>1)
    {
      $previousPage = "<a href=\"".$this->pageStr.($this->page-1)."\">上一页</a>";
    }
    //如果没到尾页则显示下一页连接
    if($this->page<$this->pageCount)
    {
      $nextPage = "<a href=\"".$this->pageStr.($this->page+1)."\">下一页</a>";
    }
    //如果没到尾页则显示尾页连接
    if($this->page<$this->pageCount)
    {
      $lastPage = "<a href=\"".$this->pageStr.$this->pageCount."\">尾页</a>";
    }
    //所有页码列表
    for($counter=1;$counter<=$this->pageCount;$counter++)
    {
      if($this->page == $counter)
      {
        $currentPage = "<b>".$counter."</b>";
      }
      else
      {
        $currentPage = " "."<a href=\"".$this->pageStr.$counter."\">".$counter."</a>"." ";
      }
      $pageList .= $currentPage;
    }
    return $firstPage." ".$previousPage." ".$pageList." ".$nextPage." ".$lastPage." ";
  }
}
?>