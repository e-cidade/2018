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

//MODULO: licitacao
//CLASSE DA ENTIDADE licitaparam
class cl_licitaparam { 
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
   var $l12_instit = 0; 
   var $l12_escolherprocesso = 'f'; 
   var $l12_escolheprotocolo = 'f'; 
   var $l12_qtdediasliberacaoweb = 0; 
   var $l12_tipoliberacaoweb = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l12_instit = int4 = Instituição 
                 l12_escolherprocesso = bool = Escolher Processo de Compras 
                 l12_escolheprotocolo = bool = Processo de Protocolo do Sistema 
                 l12_qtdediasliberacaoweb = int4 = Dias de disponibilidade 
                 l12_tipoliberacaoweb = int4 = Disp. licitação na web até o julgamento 
                 ";
   //funcao construtor da classe 
   function cl_licitaparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("licitaparam"); 
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
       $this->l12_instit = ($this->l12_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["l12_instit"]:$this->l12_instit);
       $this->l12_escolherprocesso = ($this->l12_escolherprocesso == "f"?@$GLOBALS["HTTP_POST_VARS"]["l12_escolherprocesso"]:$this->l12_escolherprocesso);
       $this->l12_escolheprotocolo = ($this->l12_escolheprotocolo == "f"?@$GLOBALS["HTTP_POST_VARS"]["l12_escolheprotocolo"]:$this->l12_escolheprotocolo);
       $this->l12_qtdediasliberacaoweb = ($this->l12_qtdediasliberacaoweb == ""?@$GLOBALS["HTTP_POST_VARS"]["l12_qtdediasliberacaoweb"]:$this->l12_qtdediasliberacaoweb);
       $this->l12_tipoliberacaoweb = ($this->l12_tipoliberacaoweb == ""?@$GLOBALS["HTTP_POST_VARS"]["l12_tipoliberacaoweb"]:$this->l12_tipoliberacaoweb);
     }else{
       $this->l12_instit = ($this->l12_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["l12_instit"]:$this->l12_instit);
     }
   }
   // funcao para inclusao
   function incluir ($l12_instit){ 
      $this->atualizacampos();
     if($this->l12_escolherprocesso == null ){ 
       $this->erro_sql = " Campo Escolher Processo de Compras nao Informado.";
       $this->erro_campo = "l12_escolherprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l12_escolheprotocolo == null ){ 
       $this->erro_sql = " Campo Processo de Protocolo do Sistema nao Informado.";
       $this->erro_campo = "l12_escolheprotocolo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l12_qtdediasliberacaoweb == null ){ 
       $this->erro_sql = " Campo Dias de disponibilidade nao Informado.";
       $this->erro_campo = "l12_qtdediasliberacaoweb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l12_tipoliberacaoweb == null ){ 
       $this->erro_sql = " Campo Disp. licitação na web até o julgamento nao Informado.";
       $this->erro_campo = "l12_tipoliberacaoweb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->l12_instit = $l12_instit; 
     if(($this->l12_instit == null) || ($this->l12_instit == "") ){ 
       $this->erro_sql = " Campo l12_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into licitaparam(
                                       l12_instit 
                                      ,l12_escolherprocesso 
                                      ,l12_escolheprotocolo 
                                      ,l12_qtdediasliberacaoweb 
                                      ,l12_tipoliberacaoweb 
                       )
                values (
                                $this->l12_instit 
                               ,'$this->l12_escolherprocesso' 
                               ,'$this->l12_escolheprotocolo' 
                               ,$this->l12_qtdediasliberacaoweb 
                               ,$this->l12_tipoliberacaoweb 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "parametros do modulo da licitacao ($this->l12_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "parametros do modulo da licitacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "parametros do modulo da licitacao ($this->l12_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l12_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l12_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11887,'$this->l12_instit','I')");
       $resac = db_query("insert into db_acount values($acount,2055,11887,'','".AddSlashes(pg_result($resaco,0,'l12_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2055,11888,'','".AddSlashes(pg_result($resaco,0,'l12_escolherprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2055,15697,'','".AddSlashes(pg_result($resaco,0,'l12_escolheprotocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2055,17211,'','".AddSlashes(pg_result($resaco,0,'l12_qtdediasliberacaoweb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2055,17210,'','".AddSlashes(pg_result($resaco,0,'l12_tipoliberacaoweb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l12_instit=null) { 
      $this->atualizacampos();
     $sql = " update licitaparam set ";
     $virgula = "";
     if(trim($this->l12_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l12_instit"])){ 
       $sql  .= $virgula." l12_instit = $this->l12_instit ";
       $virgula = ",";
       if(trim($this->l12_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "l12_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l12_escolherprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l12_escolherprocesso"])){ 
       $sql  .= $virgula." l12_escolherprocesso = '$this->l12_escolherprocesso' ";
       $virgula = ",";
       if(trim($this->l12_escolherprocesso) == null ){ 
         $this->erro_sql = " Campo Escolher Processo de Compras nao Informado.";
         $this->erro_campo = "l12_escolherprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l12_escolheprotocolo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l12_escolheprotocolo"])){ 
       $sql  .= $virgula." l12_escolheprotocolo = '$this->l12_escolheprotocolo' ";
       $virgula = ",";
       if(trim($this->l12_escolheprotocolo) == null ){ 
         $this->erro_sql = " Campo Processo de Protocolo do Sistema nao Informado.";
         $this->erro_campo = "l12_escolheprotocolo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l12_qtdediasliberacaoweb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l12_qtdediasliberacaoweb"])){ 
       $sql  .= $virgula." l12_qtdediasliberacaoweb = $this->l12_qtdediasliberacaoweb ";
       $virgula = ",";
       if(trim($this->l12_qtdediasliberacaoweb) == null ){ 
         $this->erro_sql = " Campo Dias de disponibilidade nao Informado.";
         $this->erro_campo = "l12_qtdediasliberacaoweb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l12_tipoliberacaoweb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l12_tipoliberacaoweb"])){ 
       $sql  .= $virgula." l12_tipoliberacaoweb = $this->l12_tipoliberacaoweb ";
       $virgula = ",";
       if(trim($this->l12_tipoliberacaoweb) == null ){ 
         $this->erro_sql = " Campo Disp. licitação na web até o julgamento nao Informado.";
         $this->erro_campo = "l12_tipoliberacaoweb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l12_instit!=null){
       $sql .= " l12_instit = $this->l12_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l12_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11887,'$this->l12_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l12_instit"]) || $this->l12_instit != "")
           $resac = db_query("insert into db_acount values($acount,2055,11887,'".AddSlashes(pg_result($resaco,$conresaco,'l12_instit'))."','$this->l12_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l12_escolherprocesso"]) || $this->l12_escolherprocesso != "")
           $resac = db_query("insert into db_acount values($acount,2055,11888,'".AddSlashes(pg_result($resaco,$conresaco,'l12_escolherprocesso'))."','$this->l12_escolherprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l12_escolheprotocolo"]) || $this->l12_escolheprotocolo != "")
           $resac = db_query("insert into db_acount values($acount,2055,15697,'".AddSlashes(pg_result($resaco,$conresaco,'l12_escolheprotocolo'))."','$this->l12_escolheprotocolo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l12_qtdediasliberacaoweb"]) || $this->l12_qtdediasliberacaoweb != "")
           $resac = db_query("insert into db_acount values($acount,2055,17211,'".AddSlashes(pg_result($resaco,$conresaco,'l12_qtdediasliberacaoweb'))."','$this->l12_qtdediasliberacaoweb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l12_tipoliberacaoweb"]) || $this->l12_tipoliberacaoweb != "")
           $resac = db_query("insert into db_acount values($acount,2055,17210,'".AddSlashes(pg_result($resaco,$conresaco,'l12_tipoliberacaoweb'))."','$this->l12_tipoliberacaoweb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parametros do modulo da licitacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l12_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "parametros do modulo da licitacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l12_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l12_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l12_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l12_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11887,'$l12_instit','E')");
         $resac = db_query("insert into db_acount values($acount,2055,11887,'','".AddSlashes(pg_result($resaco,$iresaco,'l12_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2055,11888,'','".AddSlashes(pg_result($resaco,$iresaco,'l12_escolherprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2055,15697,'','".AddSlashes(pg_result($resaco,$iresaco,'l12_escolheprotocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2055,17211,'','".AddSlashes(pg_result($resaco,$iresaco,'l12_qtdediasliberacaoweb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2055,17210,'','".AddSlashes(pg_result($resaco,$iresaco,'l12_tipoliberacaoweb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from licitaparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l12_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l12_instit = $l12_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parametros do modulo da licitacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l12_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "parametros do modulo da licitacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l12_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l12_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:licitaparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $l12_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from licitaparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($l12_instit!=null ){
         $sql2 .= " where licitaparam.l12_instit = $l12_instit "; 
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
   // funcao do sql 
   function sql_query_file ( $l12_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from licitaparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($l12_instit!=null ){
         $sql2 .= " where licitaparam.l12_instit = $l12_instit "; 
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