<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE liclicitaata
class cl_liclicitaata { 
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
   var $l39_sequencial = 0; 
   var $l39_arquivo = 0; 
   var $l39_arqnome = null; 
   var $l39_liclicita = 0; 
   var $l39_posicaoinicial = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l39_sequencial = int4 = Sequencial 
                 l39_arquivo = oid = Arquivo 
                 l39_arqnome = varchar(50) = Nome Arquivo 
                 l39_liclicita = int4 = Licitação 
                 l39_posicaoinicial = bool = Posição Inicial do Registro 
                 ";
   //funcao construtor da classe 
   function cl_liclicitaata() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitaata"); 
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
       $this->l39_sequencial = ($this->l39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l39_sequencial"]:$this->l39_sequencial);
       $this->l39_arquivo = ($this->l39_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["l39_arquivo"]:$this->l39_arquivo);
       $this->l39_arqnome = ($this->l39_arqnome == ""?@$GLOBALS["HTTP_POST_VARS"]["l39_arqnome"]:$this->l39_arqnome);
       $this->l39_liclicita = ($this->l39_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l39_liclicita"]:$this->l39_liclicita);
       $this->l39_posicaoinicial = ($this->l39_posicaoinicial == "f"?@$GLOBALS["HTTP_POST_VARS"]["l39_posicaoinicial"]:$this->l39_posicaoinicial);
     }else{
       $this->l39_sequencial = ($this->l39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l39_sequencial"]:$this->l39_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l39_sequencial){ 
      $this->atualizacampos();
     if($this->l39_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "l39_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l39_arqnome == null ){ 
       $this->erro_sql = " Campo Nome Arquivo nao Informado.";
       $this->erro_campo = "l39_arqnome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l39_liclicita == null ){ 
       $this->erro_sql = " Campo Licitação nao Informado.";
       $this->erro_campo = "l39_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l39_posicaoinicial == null ){ 
       $this->erro_sql = " Campo Posição Inicial do Registro nao Informado.";
       $this->erro_campo = "l39_posicaoinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l39_sequencial == "" || $l39_sequencial == null ){
       $result = db_query("select nextval('liclicitaata_l39_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaata_l39_sequencial_seq do campo: l39_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l39_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitaata_l39_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l39_sequencial)){
         $this->erro_sql = " Campo l39_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l39_sequencial = $l39_sequencial; 
       }
     }
     if(($this->l39_sequencial == null) || ($this->l39_sequencial == "") ){ 
       $this->erro_sql = " Campo l39_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitaata(
                                       l39_sequencial 
                                      ,l39_arquivo 
                                      ,l39_arqnome 
                                      ,l39_liclicita 
                                      ,l39_posicaoinicial 
                       )
                values (
                                $this->l39_sequencial 
                               ,$this->l39_arquivo 
                               ,'$this->l39_arqnome' 
                               ,$this->l39_liclicita 
                               ,'$this->l39_posicaoinicial' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicitaata ($this->l39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicitaata já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicitaata ($this->l39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l39_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l39_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15634,'$this->l39_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2742,15634,'','".AddSlashes(pg_result($resaco,0,'l39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2742,15635,'','".AddSlashes(pg_result($resaco,0,'l39_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2742,15636,'','".AddSlashes(pg_result($resaco,0,'l39_arqnome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2742,15637,'','".AddSlashes(pg_result($resaco,0,'l39_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2742,17713,'','".AddSlashes(pg_result($resaco,0,'l39_posicaoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l39_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liclicitaata set ";
     $virgula = "";
     if(trim($this->l39_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l39_sequencial"])){ 
       $sql  .= $virgula." l39_sequencial = $this->l39_sequencial ";
       $virgula = ",";
       if(trim($this->l39_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "l39_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l39_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l39_arquivo"])){ 
       $sql  .= $virgula." l39_arquivo = $this->l39_arquivo ";
       $virgula = ",";
       if(trim($this->l39_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "l39_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l39_arqnome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l39_arqnome"])){ 
       $sql  .= $virgula." l39_arqnome = '$this->l39_arqnome' ";
       $virgula = ",";
       if(trim($this->l39_arqnome) == null ){ 
         $this->erro_sql = " Campo Nome Arquivo nao Informado.";
         $this->erro_campo = "l39_arqnome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l39_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l39_liclicita"])){ 
       $sql  .= $virgula." l39_liclicita = $this->l39_liclicita ";
       $virgula = ",";
       if(trim($this->l39_liclicita) == null ){ 
         $this->erro_sql = " Campo Licitação nao Informado.";
         $this->erro_campo = "l39_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l39_posicaoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l39_posicaoinicial"])){ 
       $sql  .= $virgula." l39_posicaoinicial = '$this->l39_posicaoinicial' ";
       $virgula = ",";
       if(trim($this->l39_posicaoinicial) == null ){ 
         $this->erro_sql = " Campo Posição Inicial do Registro nao Informado.";
         $this->erro_campo = "l39_posicaoinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l39_sequencial!=null){
       $sql .= " l39_sequencial = $this->l39_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l39_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15634,'$this->l39_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l39_sequencial"]) || $this->l39_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2742,15634,'".AddSlashes(pg_result($resaco,$conresaco,'l39_sequencial'))."','$this->l39_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l39_arquivo"]) || $this->l39_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,2742,15635,'".AddSlashes(pg_result($resaco,$conresaco,'l39_arquivo'))."','$this->l39_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l39_arqnome"]) || $this->l39_arqnome != "")
           $resac = db_query("insert into db_acount values($acount,2742,15636,'".AddSlashes(pg_result($resaco,$conresaco,'l39_arqnome'))."','$this->l39_arqnome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l39_liclicita"]) || $this->l39_liclicita != "")
           $resac = db_query("insert into db_acount values($acount,2742,15637,'".AddSlashes(pg_result($resaco,$conresaco,'l39_liclicita'))."','$this->l39_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l39_posicaoinicial"]) || $this->l39_posicaoinicial != "")
           $resac = db_query("insert into db_acount values($acount,2742,17713,'".AddSlashes(pg_result($resaco,$conresaco,'l39_posicaoinicial'))."','$this->l39_posicaoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaata nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaata nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l39_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l39_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15634,'$l39_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2742,15634,'','".AddSlashes(pg_result($resaco,$iresaco,'l39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2742,15635,'','".AddSlashes(pg_result($resaco,$iresaco,'l39_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2742,15636,'','".AddSlashes(pg_result($resaco,$iresaco,'l39_arqnome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2742,15637,'','".AddSlashes(pg_result($resaco,$iresaco,'l39_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2742,17713,'','".AddSlashes(pg_result($resaco,$iresaco,'l39_posicaoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitaata
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l39_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l39_sequencial = $l39_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaata nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaata nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l39_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaata";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $l39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaata ";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitaata.l39_liclicita";
     $sql .= "      inner join db_config  on  db_config.codigo = liclicita.l20_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql .= "      inner join licsituacao  on  licsituacao.l08_sequencial = liclicita.l20_licsituacao";
     $sql2 = "";
     if($dbwhere==""){
       if($l39_sequencial!=null ){
         $sql2 .= " where liclicitaata.l39_sequencial = $l39_sequencial "; 
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
   function sql_query_file ( $l39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaata ";
     $sql2 = "";
     if($dbwhere==""){
       if($l39_sequencial!=null ){
         $sql2 .= " where liclicitaata.l39_sequencial = $l39_sequencial "; 
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