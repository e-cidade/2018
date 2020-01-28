<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcorgao
class cl_orcorgao { 
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
   var $o40_anousu = 0; 
   var $o40_orgao = 0; 
   var $o40_codtri = null; 
   var $o40_descr = null; 
   var $o40_instit = 0; 
   var $o40_finali = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o40_anousu = int4 = Exercício 
                 o40_orgao = int4 = Órgão 
                 o40_codtri = varchar(2) = Código Tribunal 
                 o40_descr = varchar(50) = Descrição 
                 o40_instit = int4 = codigo da instituicao 
                 o40_finali = text = Finalidade 
                 ";
   //funcao construtor da classe 
   function cl_orcorgao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcorgao"); 
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
       $this->o40_anousu = ($this->o40_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_anousu"]:$this->o40_anousu);
       $this->o40_orgao = ($this->o40_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_orgao"]:$this->o40_orgao);
       $this->o40_codtri = ($this->o40_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_codtri"]:$this->o40_codtri);
       $this->o40_descr = ($this->o40_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_descr"]:$this->o40_descr);
       $this->o40_instit = ($this->o40_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_instit"]:$this->o40_instit);
       $this->o40_finali = ($this->o40_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_finali"]:$this->o40_finali);
     }else{
       $this->o40_anousu = ($this->o40_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_anousu"]:$this->o40_anousu);
       $this->o40_orgao = ($this->o40_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o40_orgao"]:$this->o40_orgao);
     }
   }
   // funcao para inclusao
   function incluir ($o40_anousu,$o40_orgao){ 
      $this->atualizacampos();
     if($this->o40_codtri == null ){ 
       $this->erro_sql = " Campo Código Tribunal nao Informado.";
       $this->erro_campo = "o40_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o40_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o40_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o40_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "o40_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o40_anousu = $o40_anousu; 
       $this->o40_orgao = $o40_orgao; 
     if(($this->o40_anousu == null) || ($this->o40_anousu == "") ){ 
       $this->erro_sql = " Campo o40_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o40_orgao == null) || ($this->o40_orgao == "") ){ 
       $this->erro_sql = " Campo o40_orgao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcorgao(
                                       o40_anousu 
                                      ,o40_orgao 
                                      ,o40_codtri 
                                      ,o40_descr 
                                      ,o40_instit 
                                      ,o40_finali 
                       )
                values (
                                $this->o40_anousu 
                               ,$this->o40_orgao 
                               ,'$this->o40_codtri' 
                               ,'$this->o40_descr' 
                               ,$this->o40_instit 
                               ,'$this->o40_finali' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Orgãos  ($this->o40_anousu."-".$this->o40_orgao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Orgãos  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Orgãos  ($this->o40_anousu."-".$this->o40_orgao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o40_anousu."-".$this->o40_orgao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o40_anousu,$this->o40_orgao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5337,'$this->o40_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5338,'$this->o40_orgao','I')");
       $resac = db_query("insert into db_acount values($acount,756,5337,'','".AddSlashes(pg_result($resaco,0,'o40_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,756,5338,'','".AddSlashes(pg_result($resaco,0,'o40_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,756,5339,'','".AddSlashes(pg_result($resaco,0,'o40_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,756,5340,'','".AddSlashes(pg_result($resaco,0,'o40_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,756,5341,'','".AddSlashes(pg_result($resaco,0,'o40_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,756,6832,'','".AddSlashes(pg_result($resaco,0,'o40_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o40_anousu=null,$o40_orgao=null) { 
      $this->atualizacampos();
     $sql = " update orcorgao set ";
     $virgula = "";
     if(trim($this->o40_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o40_anousu"])){ 
       $sql  .= $virgula." o40_anousu = $this->o40_anousu ";
       $virgula = ",";
       if(trim($this->o40_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o40_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o40_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o40_orgao"])){ 
       $sql  .= $virgula." o40_orgao = $this->o40_orgao ";
       $virgula = ",";
       if(trim($this->o40_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "o40_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o40_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o40_codtri"])){ 
       $sql  .= $virgula." o40_codtri = '$this->o40_codtri' ";
       $virgula = ",";
       if(trim($this->o40_codtri) == null ){ 
         $this->erro_sql = " Campo Código Tribunal nao Informado.";
         $this->erro_campo = "o40_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o40_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o40_descr"])){ 
       $sql  .= $virgula." o40_descr = '$this->o40_descr' ";
       $virgula = ",";
       if(trim($this->o40_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o40_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o40_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o40_instit"])){ 
       $sql  .= $virgula." o40_instit = $this->o40_instit ";
       $virgula = ",";
       if(trim($this->o40_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "o40_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o40_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o40_finali"])){ 
       $sql  .= $virgula." o40_finali = '$this->o40_finali' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o40_anousu!=null){
       $sql .= " o40_anousu = $this->o40_anousu";
     }
     if($o40_orgao!=null){
       $sql .= " and  o40_orgao = $this->o40_orgao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o40_anousu,$this->o40_orgao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5337,'$this->o40_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5338,'$this->o40_orgao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o40_anousu"]))
           $resac = db_query("insert into db_acount values($acount,756,5337,'".AddSlashes(pg_result($resaco,$conresaco,'o40_anousu'))."','$this->o40_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o40_orgao"]))
           $resac = db_query("insert into db_acount values($acount,756,5338,'".AddSlashes(pg_result($resaco,$conresaco,'o40_orgao'))."','$this->o40_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o40_codtri"]))
           $resac = db_query("insert into db_acount values($acount,756,5339,'".AddSlashes(pg_result($resaco,$conresaco,'o40_codtri'))."','$this->o40_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o40_descr"]))
           $resac = db_query("insert into db_acount values($acount,756,5340,'".AddSlashes(pg_result($resaco,$conresaco,'o40_descr'))."','$this->o40_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o40_instit"]))
           $resac = db_query("insert into db_acount values($acount,756,5341,'".AddSlashes(pg_result($resaco,$conresaco,'o40_instit'))."','$this->o40_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o40_finali"]))
           $resac = db_query("insert into db_acount values($acount,756,6832,'".AddSlashes(pg_result($resaco,$conresaco,'o40_finali'))."','$this->o40_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Orgãos  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o40_anousu."-".$this->o40_orgao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Orgãos  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o40_anousu."-".$this->o40_orgao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o40_anousu."-".$this->o40_orgao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o40_anousu=null,$o40_orgao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o40_anousu,$o40_orgao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5337,'$o40_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5338,'$o40_orgao','E')");
         $resac = db_query("insert into db_acount values($acount,756,5337,'','".AddSlashes(pg_result($resaco,$iresaco,'o40_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,756,5338,'','".AddSlashes(pg_result($resaco,$iresaco,'o40_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,756,5339,'','".AddSlashes(pg_result($resaco,$iresaco,'o40_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,756,5340,'','".AddSlashes(pg_result($resaco,$iresaco,'o40_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,756,5341,'','".AddSlashes(pg_result($resaco,$iresaco,'o40_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,756,6832,'','".AddSlashes(pg_result($resaco,$iresaco,'o40_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcorgao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o40_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o40_anousu = $o40_anousu ";
        }
        if($o40_orgao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o40_orgao = $o40_orgao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Orgãos  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o40_anousu."-".$o40_orgao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Orgãos  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o40_anousu."-".$o40_orgao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o40_anousu."-".$o40_orgao;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcorgao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o40_anousu=null,$o40_orgao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcorgao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcorgao.o40_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($o40_anousu!=null ){
         $sql2 .= " where orcorgao.o40_anousu = $o40_anousu "; 
       } 
       if($o40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcorgao.o40_orgao = $o40_orgao "; 
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

   function sql_query_file ( $o40_anousu=null,$o40_orgao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcorgao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o40_anousu!=null ){
         $sql2 .= " where orcorgao.o40_anousu = $o40_anousu ";
       }
       if($o40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcorgao.o40_orgao = $o40_orgao ";
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
   function sql_query_razao( $o40_anousu=null,$o40_orgao=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcorgao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcorgao.o40_instit";
     $sql .= "      inner join orcdotacao on  o58_orgao=o40_orgao and o58_anousu=o40_anousu ";
     $sql .= "      inner join conlancamdot on c73_coddot=orcdotacao.o58_coddot  and c73_anousu=orcdotacao.o58_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($o40_anousu!=null ){
         $sql2 .= " where orcorgao.o40_anousu = $o40_anousu ";
       }
       if($o40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcorgao.o40_orgao = $o40_orgao ";
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
function sql_query_orgao( $o40_anousu=null,$o40_orgao=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcorgao ";
     $sql .= "      inner join db_departorg on  db01_orgao=o40_orgao ";
     $sql .= "      inner join db_depart    on  db01_coddepto=coddepto ";
     $sql2 = "";
     if($dbwhere==""){
       if($o40_anousu!=null ){
         $sql2 .= " where orcorgao.o40_anousu = $o40_anousu ";
       }
       if($o40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcorgao.o40_orgao = $o40_orgao ";
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
   function sql_query_dotacao ( $o40_anousu=null,$o40_orgao=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcorgao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcorgao.o40_instit ";
     $sql .= "      inner join orcdotacao on orcdotacao.o58_anousu = orcorgao.o40_anousu ";
     $sql .= "                            and orcdotacao.o58_orgao = orcorgao.o40_orgao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o40_anousu!=null ){
         $sql2 .= " where orcorgao.o40_anousu = $o40_anousu "; 
       } 
       if($o40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcorgao.o40_orgao = $o40_orgao "; 
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