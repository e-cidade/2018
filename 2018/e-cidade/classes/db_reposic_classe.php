<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: pessoal
//CLASSE DA ENTIDADE reposic
class cl_reposic { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $r64_anousu = 0; 
   var $r64_mesusu = 0; 
   var $r64_regist = 0; 
   var $r64_subpes = null; 
   var $r64_percen = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r64_anousu = int4 = Ano do Exercicio 
                 r64_mesusu = int4 = Mes do Exercicio 
                 r64_regist = int4 = Codigo do Funcionario 
                 r64_subpes = char(     7) = ano/mes da reposicao 
                 r64_percen = float8 = Percentual de reposicao 
                 ";
   //funcao construtor da classe 
   function cl_reposic() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("reposic"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->r64_anousu = ($this->r64_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_anousu"]:$this->r64_anousu);
       $this->r64_mesusu = ($this->r64_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_mesusu"]:$this->r64_mesusu);
       $this->r64_regist = ($this->r64_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_regist"]:$this->r64_regist);
       $this->r64_subpes = ($this->r64_subpes == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_subpes"]:$this->r64_subpes);
       $this->r64_percen = ($this->r64_percen == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_percen"]:$this->r64_percen);
     }else{
       $this->r64_anousu = ($this->r64_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_anousu"]:$this->r64_anousu);
       $this->r64_mesusu = ($this->r64_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_mesusu"]:$this->r64_mesusu);
       $this->r64_regist = ($this->r64_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_regist"]:$this->r64_regist);
       $this->r64_subpes = ($this->r64_subpes == ""?@$GLOBALS["HTTP_POST_VARS"]["r64_subpes"]:$this->r64_subpes);
     }
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r64_anousu,$this->r64_mesusu,$this->r64_regisr,$this->r64_subpes);
   }
   // funcao para inclusao
   function incluir ($r64_anousu,$r64_mesusu,$r64_regist,$r64_subpes){ 
      $this->atualizacampos();
     if($this->r64_percen == null ){ 
       $this->erro_sql = " Campo Percentual de reposicao nao Informado.";
       $this->erro_campo = "r64_percen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r64_anousu = $r64_anousu; 
       $this->r64_mesusu = $r64_mesusu; 
       $this->r64_regist = $r64_regist; 
       $this->r64_subpes = $r64_subpes; 
     if(($this->r64_anousu == null) || ($this->r64_anousu == "") ){ 
       $this->erro_sql = " Campo r64_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r64_mesusu == null) || ($this->r64_mesusu == "") ){ 
       $this->erro_sql = " Campo r64_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r64_regist == null) || ($this->r64_regist == "") ){ 
       $this->erro_sql = " Campo r64_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r64_subpes == null) || ($this->r64_subpes == "") ){ 
       $this->erro_sql = " Campo r64_subpes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into reposic(
                                       r64_anousu 
                                      ,r64_mesusu 
                                      ,r64_regist 
                                      ,r64_subpes 
                                      ,r64_percen 
                       )
                values (
                                $this->r64_anousu 
                               ,$this->r64_mesusu 
                               ,$this->r64_regist 
                               ,'$this->r64_subpes' 
                               ,$this->r64_percen 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reposicao Salarial ($this->r64_anousu."-".$this->r64_mesusu."-".$this->r64_regist."-".$this->r64_subpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reposicao Salarial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reposicao Salarial ($this->r64_anousu."-".$this->r64_mesusu."-".$this->r64_regist."-".$this->r64_subpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r64_anousu."-".$this->r64_mesusu."-".$this->r64_regist."-".$this->r64_subpes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r64_anousu,$this->r64_mesusu,$this->r64_regist,$this->r64_subpes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4412,'$this->r64_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4413,'$this->r64_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4414,'$this->r64_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4415,'$this->r64_subpes','I')");
       $resac = db_query("insert into db_acount values($acount,588,4412,'','".AddSlashes(pg_result($resaco,0,'r64_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,588,4413,'','".AddSlashes(pg_result($resaco,0,'r64_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,588,4414,'','".AddSlashes(pg_result($resaco,0,'r64_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,588,4415,'','".AddSlashes(pg_result($resaco,0,'r64_subpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,588,4416,'','".AddSlashes(pg_result($resaco,0,'r64_percen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r64_anousu=null,$r64_mesusu=null,$r64_regist=null,$r64_subpes=null) { 
      $this->atualizacampos();
     $sql = " update reposic set ";
     $virgula = "";
     if(trim($this->r64_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r64_anousu"])){ 
       $sql  .= $virgula." r64_anousu = $this->r64_anousu ";
       $virgula = ",";
       if(trim($this->r64_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r64_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r64_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r64_mesusu"])){ 
       $sql  .= $virgula." r64_mesusu = $this->r64_mesusu ";
       $virgula = ",";
       if(trim($this->r64_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r64_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r64_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r64_regist"])){ 
       $sql  .= $virgula." r64_regist = $this->r64_regist ";
       $virgula = ",";
       if(trim($this->r64_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r64_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r64_subpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r64_subpes"])){ 
       $sql  .= $virgula." r64_subpes = '$this->r64_subpes' ";
       $virgula = ",";
       if(trim($this->r64_subpes) == null ){ 
         $this->erro_sql = " Campo ano/mes da reposicao nao Informado.";
         $this->erro_campo = "r64_subpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r64_percen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r64_percen"])){ 
       $sql  .= $virgula." r64_percen = $this->r64_percen ";
       $virgula = ",";
       if(trim($this->r64_percen) == null ){ 
         $this->erro_sql = " Campo Percentual de reposicao nao Informado.";
         $this->erro_campo = "r64_percen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r64_anousu!=null){
       $sql .= " r64_anousu = $this->r64_anousu";
     }
     if($r64_mesusu!=null){
       $sql .= " and  r64_mesusu = $this->r64_mesusu";
     }
     if($r64_regist!=null){
       $sql .= " and  r64_regist = $this->r64_regist";
     }
     if($r64_subpes!=null){
       $sql .= " and  r64_subpes = '$this->r64_subpes'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r64_anousu,$this->r64_mesusu,$this->r64_regist,$this->r64_subpes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4412,'$this->r64_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4413,'$this->r64_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4414,'$this->r64_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4415,'$this->r64_subpes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r64_anousu"]))
           $resac = db_query("insert into db_acount values($acount,588,4412,'".AddSlashes(pg_result($resaco,$conresaco,'r64_anousu'))."','$this->r64_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r64_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,588,4413,'".AddSlashes(pg_result($resaco,$conresaco,'r64_mesusu'))."','$this->r64_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r64_regist"]))
           $resac = db_query("insert into db_acount values($acount,588,4414,'".AddSlashes(pg_result($resaco,$conresaco,'r64_regist'))."','$this->r64_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r64_subpes"]))
           $resac = db_query("insert into db_acount values($acount,588,4415,'".AddSlashes(pg_result($resaco,$conresaco,'r64_subpes'))."','$this->r64_subpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r64_percen"]))
           $resac = db_query("insert into db_acount values($acount,588,4416,'".AddSlashes(pg_result($resaco,$conresaco,'r64_percen'))."','$this->r64_percen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reposicao Salarial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r64_anousu."-".$this->r64_mesusu."-".$this->r64_regist."-".$this->r64_subpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reposicao Salarial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r64_anousu."-".$this->r64_mesusu."-".$this->r64_regist."-".$this->r64_subpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r64_anousu."-".$this->r64_mesusu."-".$this->r64_regist."-".$this->r64_subpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r64_anousu=null,$r64_mesusu=null,$r64_regist=null,$r64_subpes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r64_anousu,$r64_mesusu,$r64_regist,$r64_subpes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4412,'$r64_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4413,'$r64_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4414,'$r64_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4415,'$r64_subpes','E')");
         $resac = db_query("insert into db_acount values($acount,588,4412,'','".AddSlashes(pg_result($resaco,$iresaco,'r64_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,588,4413,'','".AddSlashes(pg_result($resaco,$iresaco,'r64_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,588,4414,'','".AddSlashes(pg_result($resaco,$iresaco,'r64_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,588,4415,'','".AddSlashes(pg_result($resaco,$iresaco,'r64_subpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,588,4416,'','".AddSlashes(pg_result($resaco,$iresaco,'r64_percen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from reposic
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r64_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r64_anousu = $r64_anousu ";
        }
        if($r64_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r64_mesusu = $r64_mesusu ";
        }
        if($r64_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r64_regist = $r64_regist ";
        }
        if($r64_subpes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r64_subpes = '$r64_subpes' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reposicao Salarial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r64_anousu."-".$r64_mesusu."-".$r64_regist."-".$r64_subpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reposicao Salarial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r64_anousu."-".$r64_mesusu."-".$r64_regist."-".$r64_subpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r64_anousu."-".$r64_mesusu."-".$r64_regist."-".$r64_subpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:reposic";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r64_anousu=null,$r64_mesusu=null,$r64_regist=null,$r64_subpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from reposic ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = reposic.r64_anousu and  pessoal.r01_mesusu = reposic.r64_mesusu and  pessoal.r01_regist = reposic.r64_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  on  inssirf.r33_anousu = pessoal.r01_anousu and  inssirf.r33_mesusu = pessoal.r01_mesusu and  inssirf.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as b on   b.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as c on   c.r37_anousu = pessoal.r01_anousu and   c.r37_mesusu = pessoal.r01_mesusu and   c.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as d on   d.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu and   d.r37_mesusu = pessoal.r01_mesusu and   d.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r64_anousu!=null ){
         $sql2 .= " where reposic.r64_anousu = $r64_anousu "; 
       } 
       if($r64_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " reposic.r64_mesusu = $r64_mesusu "; 
       } 
       if($r64_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " reposic.r64_regist = $r64_regist "; 
       } 
       if($r64_subpes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " reposic.r64_subpes = '$r64_subpes' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $r64_anousu=null,$r64_mesusu=null,$r64_regist=null,$r64_subpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from reposic ";
     $sql2 = "";
     if($dbwhere==""){
       if($r64_anousu!=null ){
         $sql2 .= " where reposic.r64_anousu = $r64_anousu "; 
       } 
       if($r64_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " reposic.r64_mesusu = $r64_mesusu "; 
       } 
       if($r64_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " reposic.r64_regist = $r64_regist "; 
       } 
       if($r64_subpes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " reposic.r64_subpes = '$r64_subpes' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>