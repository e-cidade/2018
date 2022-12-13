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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcfuncaorp
class cl_orcfuncaorp { 
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
   var $o30_anousu = 0; 
   var $o30_funcao = 0; 
   var $o30_descr = null; 
   var $o30_codtri = null; 
   var $o30_finali = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o30_anousu = int8 = Exercício 
                 o30_funcao = int4 = Função 
                 o30_descr = varchar(40) = Descrição 
                 o30_codtri = varchar(10) = Código do tribunal 
                 o30_finali = text = Finalidade 
                 ";
   //funcao construtor da classe 
   function cl_orcfuncaorp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcfuncaorp"); 
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
       $this->o30_anousu = ($this->o30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_anousu"]:$this->o30_anousu);
       $this->o30_funcao = ($this->o30_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_funcao"]:$this->o30_funcao);
       $this->o30_descr = ($this->o30_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_descr"]:$this->o30_descr);
       $this->o30_codtri = ($this->o30_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_codtri"]:$this->o30_codtri);
       $this->o30_finali = ($this->o30_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_finali"]:$this->o30_finali);
     }else{
       $this->o30_anousu = ($this->o30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_anousu"]:$this->o30_anousu);
       $this->o30_funcao = ($this->o30_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o30_funcao"]:$this->o30_funcao);
     }
   }
   // funcao para inclusao
   function incluir ($o30_anousu,$o30_funcao){ 
      $this->atualizacampos();
     if($this->o30_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o30_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o30_codtri == null ){ 
       $this->erro_sql = " Campo Código do tribunal nao Informado.";
       $this->erro_campo = "o30_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o30_anousu = $o30_anousu; 
       $this->o30_funcao = $o30_funcao; 
     if(($this->o30_anousu == null) || ($this->o30_anousu == "") ){ 
       $this->erro_sql = " Campo o30_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o30_funcao == null) || ($this->o30_funcao == "") ){ 
       $this->erro_sql = " Campo o30_funcao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcfuncaorp(
                                       o30_anousu 
                                      ,o30_funcao 
                                      ,o30_descr 
                                      ,o30_codtri 
                                      ,o30_finali 
                       )
                values (
                                $this->o30_anousu 
                               ,$this->o30_funcao 
                               ,'$this->o30_descr' 
                               ,'$this->o30_codtri' 
                               ,'$this->o30_finali' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Funções dos anos anteriores a 2005 ($this->o30_anousu."-".$this->o30_funcao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Funções dos anos anteriores a 2005 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Funções dos anos anteriores a 2005 ($this->o30_anousu."-".$this->o30_funcao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o30_anousu."-".$this->o30_funcao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o30_anousu,$this->o30_funcao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6417,'$this->o30_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6409,'$this->o30_funcao','I')");
       $resac = db_query("insert into db_acount values($acount,1052,6417,'','".AddSlashes(pg_result($resaco,0,'o30_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1052,6409,'','".AddSlashes(pg_result($resaco,0,'o30_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1052,6410,'','".AddSlashes(pg_result($resaco,0,'o30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1052,6411,'','".AddSlashes(pg_result($resaco,0,'o30_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1052,6412,'','".AddSlashes(pg_result($resaco,0,'o30_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o30_anousu=null,$o30_funcao=null) { 
      $this->atualizacampos();
     $sql = " update orcfuncaorp set ";
     $virgula = "";
     if(trim($this->o30_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o30_anousu"])){ 
       $sql  .= $virgula." o30_anousu = $this->o30_anousu ";
       $virgula = ",";
       if(trim($this->o30_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o30_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o30_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o30_funcao"])){ 
       $sql  .= $virgula." o30_funcao = $this->o30_funcao ";
       $virgula = ",";
       if(trim($this->o30_funcao) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "o30_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o30_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o30_descr"])){ 
       $sql  .= $virgula." o30_descr = '$this->o30_descr' ";
       $virgula = ",";
       if(trim($this->o30_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o30_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o30_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o30_codtri"])){ 
       $sql  .= $virgula." o30_codtri = '$this->o30_codtri' ";
       $virgula = ",";
       if(trim($this->o30_codtri) == null ){ 
         $this->erro_sql = " Campo Código do tribunal nao Informado.";
         $this->erro_campo = "o30_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o30_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o30_finali"])){ 
       $sql  .= $virgula." o30_finali = '$this->o30_finali' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o30_anousu!=null){
       $sql .= " o30_anousu = $this->o30_anousu";
     }
     if($o30_funcao!=null){
       $sql .= " and  o30_funcao = $this->o30_funcao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o30_anousu,$this->o30_funcao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6417,'$this->o30_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6409,'$this->o30_funcao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o30_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1052,6417,'".AddSlashes(pg_result($resaco,$conresaco,'o30_anousu'))."','$this->o30_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o30_funcao"]))
           $resac = db_query("insert into db_acount values($acount,1052,6409,'".AddSlashes(pg_result($resaco,$conresaco,'o30_funcao'))."','$this->o30_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o30_descr"]))
           $resac = db_query("insert into db_acount values($acount,1052,6410,'".AddSlashes(pg_result($resaco,$conresaco,'o30_descr'))."','$this->o30_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o30_codtri"]))
           $resac = db_query("insert into db_acount values($acount,1052,6411,'".AddSlashes(pg_result($resaco,$conresaco,'o30_codtri'))."','$this->o30_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o30_finali"]))
           $resac = db_query("insert into db_acount values($acount,1052,6412,'".AddSlashes(pg_result($resaco,$conresaco,'o30_finali'))."','$this->o30_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funções dos anos anteriores a 2005 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o30_anousu."-".$this->o30_funcao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funções dos anos anteriores a 2005 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o30_anousu."-".$this->o30_funcao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o30_anousu."-".$this->o30_funcao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o30_anousu=null,$o30_funcao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o30_anousu,$o30_funcao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6417,'$o30_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6409,'$o30_funcao','E')");
         $resac = db_query("insert into db_acount values($acount,1052,6417,'','".AddSlashes(pg_result($resaco,$iresaco,'o30_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1052,6409,'','".AddSlashes(pg_result($resaco,$iresaco,'o30_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1052,6410,'','".AddSlashes(pg_result($resaco,$iresaco,'o30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1052,6411,'','".AddSlashes(pg_result($resaco,$iresaco,'o30_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1052,6412,'','".AddSlashes(pg_result($resaco,$iresaco,'o30_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcfuncaorp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o30_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o30_anousu = $o30_anousu ";
        }
        if($o30_funcao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o30_funcao = $o30_funcao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funções dos anos anteriores a 2005 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o30_anousu."-".$o30_funcao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funções dos anos anteriores a 2005 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o30_anousu."-".$o30_funcao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o30_anousu."-".$o30_funcao;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcfuncaorp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>