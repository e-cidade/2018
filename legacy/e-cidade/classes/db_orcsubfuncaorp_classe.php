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
//CLASSE DA ENTIDADE orcsubfuncaorp
class cl_orcsubfuncaorp { 
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
   var $o31_anousu = 0; 
   var $o31_subfuncao = 0; 
   var $o31_descr = null; 
   var $o31_codtri = null; 
   var $o31_finali = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o31_anousu = int8 = Exercício 
                 o31_subfuncao = int4 = Sub Função 
                 o31_descr = varchar(40) = Descrição 
                 o31_codtri = varchar(10) = Código tribunal 
                 o31_finali = text = Finalidade 
                 ";
   //funcao construtor da classe 
   function cl_orcsubfuncaorp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsubfuncaorp"); 
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
       $this->o31_anousu = ($this->o31_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_anousu"]:$this->o31_anousu);
       $this->o31_subfuncao = ($this->o31_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_subfuncao"]:$this->o31_subfuncao);
       $this->o31_descr = ($this->o31_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_descr"]:$this->o31_descr);
       $this->o31_codtri = ($this->o31_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_codtri"]:$this->o31_codtri);
       $this->o31_finali = ($this->o31_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_finali"]:$this->o31_finali);
     }else{
       $this->o31_anousu = ($this->o31_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_anousu"]:$this->o31_anousu);
       $this->o31_subfuncao = ($this->o31_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o31_subfuncao"]:$this->o31_subfuncao);
     }
   }
   // funcao para inclusao
   function incluir ($o31_anousu,$o31_subfuncao){ 
      $this->atualizacampos();
     if($this->o31_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o31_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o31_codtri == null ){ 
       $this->erro_sql = " Campo Código tribunal nao Informado.";
       $this->erro_campo = "o31_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o31_anousu = $o31_anousu; 
       $this->o31_subfuncao = $o31_subfuncao; 
     if(($this->o31_anousu == null) || ($this->o31_anousu == "") ){ 
       $this->erro_sql = " Campo o31_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o31_subfuncao == null) || ($this->o31_subfuncao == "") ){ 
       $this->erro_sql = " Campo o31_subfuncao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsubfuncaorp(
                                       o31_anousu 
                                      ,o31_subfuncao 
                                      ,o31_descr 
                                      ,o31_codtri 
                                      ,o31_finali 
                       )
                values (
                                $this->o31_anousu 
                               ,$this->o31_subfuncao 
                               ,'$this->o31_descr' 
                               ,'$this->o31_codtri' 
                               ,'$this->o31_finali' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Subfunções dos anos anteriores a 2005 ($this->o31_anousu."-".$this->o31_subfuncao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Subfunções dos anos anteriores a 2005 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Subfunções dos anos anteriores a 2005 ($this->o31_anousu."-".$this->o31_subfuncao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o31_anousu."-".$this->o31_subfuncao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o31_anousu,$this->o31_subfuncao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6418,'$this->o31_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6413,'$this->o31_subfuncao','I')");
       $resac = db_query("insert into db_acount values($acount,1053,6418,'','".AddSlashes(pg_result($resaco,0,'o31_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1053,6413,'','".AddSlashes(pg_result($resaco,0,'o31_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1053,6414,'','".AddSlashes(pg_result($resaco,0,'o31_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1053,6415,'','".AddSlashes(pg_result($resaco,0,'o31_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1053,6416,'','".AddSlashes(pg_result($resaco,0,'o31_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o31_anousu=null,$o31_subfuncao=null) { 
      $this->atualizacampos();
     $sql = " update orcsubfuncaorp set ";
     $virgula = "";
     if(trim($this->o31_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o31_anousu"])){ 
       $sql  .= $virgula." o31_anousu = $this->o31_anousu ";
       $virgula = ",";
       if(trim($this->o31_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o31_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o31_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o31_subfuncao"])){ 
       $sql  .= $virgula." o31_subfuncao = $this->o31_subfuncao ";
       $virgula = ",";
       if(trim($this->o31_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Função nao Informado.";
         $this->erro_campo = "o31_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o31_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o31_descr"])){ 
       $sql  .= $virgula." o31_descr = '$this->o31_descr' ";
       $virgula = ",";
       if(trim($this->o31_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o31_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o31_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o31_codtri"])){ 
       $sql  .= $virgula." o31_codtri = '$this->o31_codtri' ";
       $virgula = ",";
       if(trim($this->o31_codtri) == null ){ 
         $this->erro_sql = " Campo Código tribunal nao Informado.";
         $this->erro_campo = "o31_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o31_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o31_finali"])){ 
       $sql  .= $virgula." o31_finali = '$this->o31_finali' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o31_anousu!=null){
       $sql .= " o31_anousu = $this->o31_anousu";
     }
     if($o31_subfuncao!=null){
       $sql .= " and  o31_subfuncao = $this->o31_subfuncao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o31_anousu,$this->o31_subfuncao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6418,'$this->o31_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6413,'$this->o31_subfuncao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o31_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1053,6418,'".AddSlashes(pg_result($resaco,$conresaco,'o31_anousu'))."','$this->o31_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o31_subfuncao"]))
           $resac = db_query("insert into db_acount values($acount,1053,6413,'".AddSlashes(pg_result($resaco,$conresaco,'o31_subfuncao'))."','$this->o31_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o31_descr"]))
           $resac = db_query("insert into db_acount values($acount,1053,6414,'".AddSlashes(pg_result($resaco,$conresaco,'o31_descr'))."','$this->o31_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o31_codtri"]))
           $resac = db_query("insert into db_acount values($acount,1053,6415,'".AddSlashes(pg_result($resaco,$conresaco,'o31_codtri'))."','$this->o31_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o31_finali"]))
           $resac = db_query("insert into db_acount values($acount,1053,6416,'".AddSlashes(pg_result($resaco,$conresaco,'o31_finali'))."','$this->o31_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subfunções dos anos anteriores a 2005 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o31_anousu."-".$this->o31_subfuncao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Subfunções dos anos anteriores a 2005 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o31_anousu."-".$this->o31_subfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o31_anousu."-".$this->o31_subfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o31_anousu=null,$o31_subfuncao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o31_anousu,$o31_subfuncao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6418,'$o31_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6413,'$o31_subfuncao','E')");
         $resac = db_query("insert into db_acount values($acount,1053,6418,'','".AddSlashes(pg_result($resaco,$iresaco,'o31_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1053,6413,'','".AddSlashes(pg_result($resaco,$iresaco,'o31_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1053,6414,'','".AddSlashes(pg_result($resaco,$iresaco,'o31_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1053,6415,'','".AddSlashes(pg_result($resaco,$iresaco,'o31_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1053,6416,'','".AddSlashes(pg_result($resaco,$iresaco,'o31_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsubfuncaorp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o31_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o31_anousu = $o31_anousu ";
        }
        if($o31_subfuncao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o31_subfuncao = $o31_subfuncao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subfunções dos anos anteriores a 2005 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o31_anousu."-".$o31_subfuncao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Subfunções dos anos anteriores a 2005 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o31_anousu."-".$o31_subfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o31_anousu."-".$o31_subfuncao;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsubfuncaorp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>