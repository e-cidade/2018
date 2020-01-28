<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE cidadaofiliacao
class cl_cidadaofiliacao { 
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
   var $ov29_sequencial = 0; 
   var $ov29_cidadao = 0; 
   var $ov29_cidadao_seq = 0; 
   var $ov29_tipofamiliar = 0; 
   var $ov29_cidadaovinculo = 0; 
   var $ov29_cidadaovinculo_seq = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov29_sequencial = int4 = Código CidadaoFiliacao 
                 ov29_cidadao = int4 = Cidadão 
                 ov29_cidadao_seq = int4 = Cidadão Sequencial 
                 ov29_tipofamiliar = int4 = Tipo Familiar 
                 ov29_cidadaovinculo = int4 = Cidadão Vínculo 
                 ov29_cidadaovinculo_seq = int4 = Cidadão Vínculo Sequencial 
                 ";
   //funcao construtor da classe 
   function cl_cidadaofiliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaofiliacao"); 
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
       $this->ov29_sequencial = ($this->ov29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_sequencial"]:$this->ov29_sequencial);
       $this->ov29_cidadao = ($this->ov29_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_cidadao"]:$this->ov29_cidadao);
       $this->ov29_cidadao_seq = ($this->ov29_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_cidadao_seq"]:$this->ov29_cidadao_seq);
       $this->ov29_tipofamiliar = ($this->ov29_tipofamiliar == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_tipofamiliar"]:$this->ov29_tipofamiliar);
       $this->ov29_cidadaovinculo = ($this->ov29_cidadaovinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_cidadaovinculo"]:$this->ov29_cidadaovinculo);
       $this->ov29_cidadaovinculo_seq = ($this->ov29_cidadaovinculo_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_cidadaovinculo_seq"]:$this->ov29_cidadaovinculo_seq);
     }else{
       $this->ov29_sequencial = ($this->ov29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov29_sequencial"]:$this->ov29_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov29_sequencial){ 
      $this->atualizacampos();
     if($this->ov29_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "ov29_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov29_cidadao_seq == null ){ 
       $this->erro_sql = " Campo Cidadão Sequencial nao Informado.";
       $this->erro_campo = "ov29_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov29_tipofamiliar == null ){ 
       $this->erro_sql = " Campo Tipo Familiar nao Informado.";
       $this->erro_campo = "ov29_tipofamiliar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov29_cidadaovinculo == null ){ 
       $this->erro_sql = " Campo Cidadão Vínculo nao Informado.";
       $this->erro_campo = "ov29_cidadaovinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov29_cidadaovinculo_seq == null ){ 
       $this->erro_sql = " Campo Cidadão Vínculo Sequencial nao Informado.";
       $this->erro_campo = "ov29_cidadaovinculo_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov29_sequencial == "" || $ov29_sequencial == null ){
       $result = db_query("select nextval('cidadaofiliacao_ov29_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaofiliacao_ov29_sequencial_seq do campo: ov29_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov29_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaofiliacao_ov29_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov29_sequencial)){
         $this->erro_sql = " Campo ov29_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov29_sequencial = $ov29_sequencial; 
       }
     }
     if(($this->ov29_sequencial == null) || ($this->ov29_sequencial == "") ){ 
       $this->erro_sql = " Campo ov29_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaofiliacao(
                                       ov29_sequencial 
                                      ,ov29_cidadao 
                                      ,ov29_cidadao_seq 
                                      ,ov29_tipofamiliar 
                                      ,ov29_cidadaovinculo 
                                      ,ov29_cidadaovinculo_seq 
                       )
                values (
                                $this->ov29_sequencial 
                               ,$this->ov29_cidadao 
                               ,$this->ov29_cidadao_seq 
                               ,$this->ov29_tipofamiliar 
                               ,$this->ov29_cidadaovinculo 
                               ,$this->ov29_cidadaovinculo_seq 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "CidadaoFiliacao ($this->ov29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "CidadaoFiliacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "CidadaoFiliacao ($this->ov29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov29_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ov29_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20152,'$this->ov29_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3618,20152,'','".AddSlashes(pg_result($resaco,0,'ov29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3618,20153,'','".AddSlashes(pg_result($resaco,0,'ov29_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3618,20154,'','".AddSlashes(pg_result($resaco,0,'ov29_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3618,20155,'','".AddSlashes(pg_result($resaco,0,'ov29_tipofamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3618,20156,'','".AddSlashes(pg_result($resaco,0,'ov29_cidadaovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3618,20157,'','".AddSlashes(pg_result($resaco,0,'ov29_cidadaovinculo_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov29_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaofiliacao set ";
     $virgula = "";
     if(trim($this->ov29_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov29_sequencial"])){ 
       $sql  .= $virgula." ov29_sequencial = $this->ov29_sequencial ";
       $virgula = ",";
       if(trim($this->ov29_sequencial) == null ){ 
         $this->erro_sql = " Campo Código CidadaoFiliacao nao Informado.";
         $this->erro_campo = "ov29_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov29_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadao"])){ 
       $sql  .= $virgula." ov29_cidadao = $this->ov29_cidadao ";
       $virgula = ",";
       if(trim($this->ov29_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "ov29_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov29_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadao_seq"])){ 
       $sql  .= $virgula." ov29_cidadao_seq = $this->ov29_cidadao_seq ";
       $virgula = ",";
       if(trim($this->ov29_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo Cidadão Sequencial nao Informado.";
         $this->erro_campo = "ov29_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov29_tipofamiliar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov29_tipofamiliar"])){ 
       $sql  .= $virgula." ov29_tipofamiliar = $this->ov29_tipofamiliar ";
       $virgula = ",";
       if(trim($this->ov29_tipofamiliar) == null ){ 
         $this->erro_sql = " Campo Tipo Familiar nao Informado.";
         $this->erro_campo = "ov29_tipofamiliar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov29_cidadaovinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadaovinculo"])){ 
       $sql  .= $virgula." ov29_cidadaovinculo = $this->ov29_cidadaovinculo ";
       $virgula = ",";
       if(trim($this->ov29_cidadaovinculo) == null ){ 
         $this->erro_sql = " Campo Cidadão Vínculo nao Informado.";
         $this->erro_campo = "ov29_cidadaovinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov29_cidadaovinculo_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadaovinculo_seq"])){ 
       $sql  .= $virgula." ov29_cidadaovinculo_seq = $this->ov29_cidadaovinculo_seq ";
       $virgula = ",";
       if(trim($this->ov29_cidadaovinculo_seq) == null ){ 
         $this->erro_sql = " Campo Cidadão Vínculo Sequencial nao Informado.";
         $this->erro_campo = "ov29_cidadaovinculo_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ov29_sequencial!=null){
       $sql .= " ov29_sequencial = $this->ov29_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ov29_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20152,'$this->ov29_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov29_sequencial"]) || $this->ov29_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3618,20152,'".AddSlashes(pg_result($resaco,$conresaco,'ov29_sequencial'))."','$this->ov29_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadao"]) || $this->ov29_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3618,20153,'".AddSlashes(pg_result($resaco,$conresaco,'ov29_cidadao'))."','$this->ov29_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadao_seq"]) || $this->ov29_cidadao_seq != "")
             $resac = db_query("insert into db_acount values($acount,3618,20154,'".AddSlashes(pg_result($resaco,$conresaco,'ov29_cidadao_seq'))."','$this->ov29_cidadao_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov29_tipofamiliar"]) || $this->ov29_tipofamiliar != "")
             $resac = db_query("insert into db_acount values($acount,3618,20155,'".AddSlashes(pg_result($resaco,$conresaco,'ov29_tipofamiliar'))."','$this->ov29_tipofamiliar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadaovinculo"]) || $this->ov29_cidadaovinculo != "")
             $resac = db_query("insert into db_acount values($acount,3618,20156,'".AddSlashes(pg_result($resaco,$conresaco,'ov29_cidadaovinculo'))."','$this->ov29_cidadaovinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ov29_cidadaovinculo_seq"]) || $this->ov29_cidadaovinculo_seq != "")
             $resac = db_query("insert into db_acount values($acount,3618,20157,'".AddSlashes(pg_result($resaco,$conresaco,'ov29_cidadaovinculo_seq'))."','$this->ov29_cidadaovinculo_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CidadaoFiliacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CidadaoFiliacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov29_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ov29_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20152,'$ov29_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3618,20152,'','".AddSlashes(pg_result($resaco,$iresaco,'ov29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3618,20153,'','".AddSlashes(pg_result($resaco,$iresaco,'ov29_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3618,20154,'','".AddSlashes(pg_result($resaco,$iresaco,'ov29_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3618,20155,'','".AddSlashes(pg_result($resaco,$iresaco,'ov29_tipofamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3618,20156,'','".AddSlashes(pg_result($resaco,$iresaco,'ov29_cidadaovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3618,20157,'','".AddSlashes(pg_result($resaco,$iresaco,'ov29_cidadaovinculo_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaofiliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov29_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov29_sequencial = $ov29_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CidadaoFiliacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CidadaoFiliacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov29_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaofiliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofiliacao ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cidadaofiliacao.ov29_cidadao and  cidadao.ov02_sequencial = cidadaofiliacao.ov29_cidadaovinculo and  cidadao.ov02_seq = cidadaofiliacao.ov29_cidadao_seq and  cidadao.ov02_seq = cidadaofiliacao.ov29_cidadaovinculo_seq";
     $sql .= "      inner join tipofamiliar  on  tipofamiliar.z14_sequencial = cidadaofiliacao.ov29_tipofamiliar";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql2 = "";
     if($dbwhere==""){
       if($ov29_sequencial!=null ){
         $sql2 .= " where cidadaofiliacao.ov29_sequencial = $ov29_sequencial "; 
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
   function sql_query_file ( $ov29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofiliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov29_sequencial!=null ){
         $sql2 .= " where cidadaofiliacao.ov29_sequencial = $ov29_sequencial "; 
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