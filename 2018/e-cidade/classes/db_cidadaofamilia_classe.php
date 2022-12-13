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

//MODULO: social
//CLASSE DA ENTIDADE cidadaofamilia
class cl_cidadaofamilia { 
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
   var $as04_sequencial = 0; 
   var $as04_dataentrevista_dia = null; 
   var $as04_dataentrevista_mes = null; 
   var $as04_dataentrevista_ano = null; 
   var $as04_dataentrevista = null; 
   var $as04_rendafamiliar = 0; 
   var $as04_dataatualizacao_dia = null; 
   var $as04_dataatualizacao_mes = null; 
   var $as04_dataatualizacao_ano = null; 
   var $as04_dataatualizacao = null; 
   var $as04_aparelhoeletricocontinuo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as04_sequencial = int4 = Código da Família 
                 as04_dataentrevista = date = Data da Entrevista 
                 as04_rendafamiliar = float8 = Renda Familiar 
                 as04_dataatualizacao = date = Data de Atualização 
                 as04_aparelhoeletricocontinuo = bool = Aparelho Eletríco Contínuo 
                 ";
   //funcao construtor da classe 
   function cl_cidadaofamilia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaofamilia"); 
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
       $this->as04_sequencial = ($this->as04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_sequencial"]:$this->as04_sequencial);
       if($this->as04_dataentrevista == ""){
         $this->as04_dataentrevista_dia = ($this->as04_dataentrevista_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista_dia"]:$this->as04_dataentrevista_dia);
         $this->as04_dataentrevista_mes = ($this->as04_dataentrevista_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista_mes"]:$this->as04_dataentrevista_mes);
         $this->as04_dataentrevista_ano = ($this->as04_dataentrevista_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista_ano"]:$this->as04_dataentrevista_ano);
         if($this->as04_dataentrevista_dia != ""){
            $this->as04_dataentrevista = $this->as04_dataentrevista_ano."-".$this->as04_dataentrevista_mes."-".$this->as04_dataentrevista_dia;
         }
       }
       $this->as04_rendafamiliar = ($this->as04_rendafamiliar == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_rendafamiliar"]:$this->as04_rendafamiliar);
       if($this->as04_dataatualizacao == ""){
         $this->as04_dataatualizacao_dia = ($this->as04_dataatualizacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao_dia"]:$this->as04_dataatualizacao_dia);
         $this->as04_dataatualizacao_mes = ($this->as04_dataatualizacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao_mes"]:$this->as04_dataatualizacao_mes);
         $this->as04_dataatualizacao_ano = ($this->as04_dataatualizacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao_ano"]:$this->as04_dataatualizacao_ano);
         if($this->as04_dataatualizacao_dia != ""){
            $this->as04_dataatualizacao = $this->as04_dataatualizacao_ano."-".$this->as04_dataatualizacao_mes."-".$this->as04_dataatualizacao_dia;
         }
       }
       $this->as04_aparelhoeletricocontinuo = ($this->as04_aparelhoeletricocontinuo == "f"?@$GLOBALS["HTTP_POST_VARS"]["as04_aparelhoeletricocontinuo"]:$this->as04_aparelhoeletricocontinuo);
     }else{
       $this->as04_sequencial = ($this->as04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as04_sequencial"]:$this->as04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as04_sequencial){ 
      $this->atualizacampos();
     if($this->as04_dataentrevista == null ){ 
       $this->erro_sql = " Campo Data da Entrevista nao Informado.";
       $this->erro_campo = "as04_dataentrevista_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as04_rendafamiliar == null ){ 
       $this->erro_sql = " Campo Renda Familiar nao Informado.";
       $this->erro_campo = "as04_rendafamiliar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as04_dataatualizacao == null ){ 
       $this->erro_sql = " Campo Data de Atualização nao Informado.";
       $this->erro_campo = "as04_dataatualizacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as04_aparelhoeletricocontinuo == null ){ 
       $this->erro_sql = " Campo Aparelho Eletríco Contínuo nao Informado.";
       $this->erro_campo = "as04_aparelhoeletricocontinuo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as04_sequencial == "" || $as04_sequencial == null ){
       $result = db_query("select nextval('cidadaofamilia_as04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaofamilia_as04_sequencial_seq do campo: as04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaofamilia_as04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as04_sequencial)){
         $this->erro_sql = " Campo as04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as04_sequencial = $as04_sequencial; 
       }
     }
     if(($this->as04_sequencial == null) || ($this->as04_sequencial == "") ){ 
       $this->erro_sql = " Campo as04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaofamilia(
                                       as04_sequencial 
                                      ,as04_dataentrevista 
                                      ,as04_rendafamiliar 
                                      ,as04_dataatualizacao 
                                      ,as04_aparelhoeletricocontinuo 
                       )
                values (
                                $this->as04_sequencial 
                               ,".($this->as04_dataentrevista == "null" || $this->as04_dataentrevista == ""?"null":"'".$this->as04_dataentrevista."'")." 
                               ,$this->as04_rendafamiliar 
                               ,".($this->as04_dataatualizacao == "null" || $this->as04_dataatualizacao == ""?"null":"'".$this->as04_dataatualizacao."'")." 
                               ,'$this->as04_aparelhoeletricocontinuo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaofamilia ($this->as04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaofamilia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaofamilia ($this->as04_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as04_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19079,'$this->as04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3394,19079,'','".AddSlashes(pg_result($resaco,0,'as04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3394,19082,'','".AddSlashes(pg_result($resaco,0,'as04_dataentrevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3394,19170,'','".AddSlashes(pg_result($resaco,0,'as04_rendafamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3394,19171,'','".AddSlashes(pg_result($resaco,0,'as04_dataatualizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3394,19646,'','".AddSlashes(pg_result($resaco,0,'as04_aparelhoeletricocontinuo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaofamilia set ";
     $virgula = "";
     if(trim($this->as04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as04_sequencial"])){ 
       $sql  .= $virgula." as04_sequencial = $this->as04_sequencial ";
       $virgula = ",";
       if(trim($this->as04_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Família nao Informado.";
         $this->erro_campo = "as04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as04_dataentrevista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista_dia"] !="") ){ 
       $sql  .= $virgula." as04_dataentrevista = '$this->as04_dataentrevista' ";
       $virgula = ",";
       if(trim($this->as04_dataentrevista) == null ){ 
         $this->erro_sql = " Campo Data da Entrevista nao Informado.";
         $this->erro_campo = "as04_dataentrevista_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista_dia"])){ 
         $sql  .= $virgula." as04_dataentrevista = null ";
         $virgula = ",";
         if(trim($this->as04_dataentrevista) == null ){ 
           $this->erro_sql = " Campo Data da Entrevista nao Informado.";
           $this->erro_campo = "as04_dataentrevista_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as04_rendafamiliar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as04_rendafamiliar"])){ 
       $sql  .= $virgula." as04_rendafamiliar = $this->as04_rendafamiliar ";
       $virgula = ",";
       if(trim($this->as04_rendafamiliar) == null ){ 
         $this->erro_sql = " Campo Renda Familiar nao Informado.";
         $this->erro_campo = "as04_rendafamiliar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as04_dataatualizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao_dia"] !="") ){ 
       $sql  .= $virgula." as04_dataatualizacao = '$this->as04_dataatualizacao' ";
       $virgula = ",";
       if(trim($this->as04_dataatualizacao) == null ){ 
         $this->erro_sql = " Campo Data de Atualização nao Informado.";
         $this->erro_campo = "as04_dataatualizacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao_dia"])){ 
         $sql  .= $virgula." as04_dataatualizacao = null ";
         $virgula = ",";
         if(trim($this->as04_dataatualizacao) == null ){ 
           $this->erro_sql = " Campo Data de Atualização nao Informado.";
           $this->erro_campo = "as04_dataatualizacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as04_aparelhoeletricocontinuo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as04_aparelhoeletricocontinuo"])){ 
       $sql  .= $virgula." as04_aparelhoeletricocontinuo = '$this->as04_aparelhoeletricocontinuo' ";
       $virgula = ",";
       if(trim($this->as04_aparelhoeletricocontinuo) == null ){ 
         $this->erro_sql = " Campo Aparelho Eletríco Contínuo nao Informado.";
         $this->erro_campo = "as04_aparelhoeletricocontinuo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as04_sequencial!=null){
       $sql .= " as04_sequencial = $this->as04_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as04_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19079,'$this->as04_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as04_sequencial"]) || $this->as04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3394,19079,'".AddSlashes(pg_result($resaco,$conresaco,'as04_sequencial'))."','$this->as04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as04_dataentrevista"]) || $this->as04_dataentrevista != "")
             $resac = db_query("insert into db_acount values($acount,3394,19082,'".AddSlashes(pg_result($resaco,$conresaco,'as04_dataentrevista'))."','$this->as04_dataentrevista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as04_rendafamiliar"]) || $this->as04_rendafamiliar != "")
             $resac = db_query("insert into db_acount values($acount,3394,19170,'".AddSlashes(pg_result($resaco,$conresaco,'as04_rendafamiliar'))."','$this->as04_rendafamiliar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as04_dataatualizacao"]) || $this->as04_dataatualizacao != "")
             $resac = db_query("insert into db_acount values($acount,3394,19171,'".AddSlashes(pg_result($resaco,$conresaco,'as04_dataatualizacao'))."','$this->as04_dataatualizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as04_aparelhoeletricocontinuo"]) || $this->as04_aparelhoeletricocontinuo != "")
             $resac = db_query("insert into db_acount values($acount,3394,19646,'".AddSlashes(pg_result($resaco,$conresaco,'as04_aparelhoeletricocontinuo'))."','$this->as04_aparelhoeletricocontinuo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaofamilia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaofamilia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as04_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as04_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19079,'$as04_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3394,19079,'','".AddSlashes(pg_result($resaco,$iresaco,'as04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3394,19082,'','".AddSlashes(pg_result($resaco,$iresaco,'as04_dataentrevista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3394,19170,'','".AddSlashes(pg_result($resaco,$iresaco,'as04_rendafamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3394,19171,'','".AddSlashes(pg_result($resaco,$iresaco,'as04_dataatualizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3394,19646,'','".AddSlashes(pg_result($resaco,$iresaco,'as04_aparelhoeletricocontinuo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaofamilia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as04_sequencial = $as04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaofamilia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaofamilia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaofamilia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofamilia ";
     $sql2 = "";
     if($dbwhere==""){
       if($as04_sequencial!=null ){
         $sql2 .= " where cidadaofamilia.as04_sequencial = $as04_sequencial "; 
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
   function sql_query_file ( $as04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaofamilia ";
     $sql2 = "";
     if($dbwhere==""){
       if($as04_sequencial!=null ){
         $sql2 .= " where cidadaofamilia.as04_sequencial = $as04_sequencial "; 
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
  function sql_query_completa ( $as04_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    
    $sql = "select ";
    if ($campos != "*") {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cidadaofamilia ";
    $sql .= "      inner join cidadaocomposicaofamiliar   on as03_cidadaofamilia = as04_sequencial";
    $sql .= "      inner join cidadao                     on as03_cidadao        = ov02_sequencial";
    $sql .= "                                            and as03_cidadao_seq    = ov02_seq       ";
    $sql .= "      left  join cidadaocadastrounico        on as02_cidadao        = ov02_sequencial";
    $sql .= "      left  join cidadaobeneficio            on as02_nis            = as08_nis";
    $sql .= "      left  join cidadaofamiliacadastrounico on as15_cidadaofamilia = as04_sequencial";
    $sql .= "      left  join localatendimentofamilia     on as23_cidadaofamilia = as04_sequencial";
    $sql .= "      left  join localatendimentosocial      on as16_sequencial     = as23_localatendimentosocial";
    
    $sql2 = "";
    if ($dbwhere == "") {
       
      if ($as04_sequencial!=null ) {
        $sql2 .= " where cidadaofamilia.as04_sequencial              = $as04_sequencial";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
   }
   function sql_query_familia_avaliacao ($as04_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cidadaofamilia ";
    $sql .= " left join cidadaofamiliaavaliacao on as06_cidadaofamilia = as04_sequencial";
    $sql2 = "";
    if ($dbwhere=="") {
       
      if ($as04_sequencial != null) {
        $sql2 .= " where cidadaofamilia.as04_sequencial              = $as04_sequencial";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_responsavel ($as04_sequencial=null, $campos="*", $ordem=null, $dbwhere="") {
    
    $sql = "select ";
    if ($campos != "*") {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cidadaofamilia ";
    $sql .= "      inner join cidadaocomposicaofamiliar   on as03_cidadaofamilia = as04_sequencial";
		$sql .= "      																	     and as03_tipofamiliar   = 0              ";    
    $sql .= "      inner join cidadao                     on as03_cidadao        = ov02_sequencial";
    $sql .= "                                            and as03_cidadao_seq    = ov02_seq       ";   
    $sql .= "      left  join cidadaocadastrounico        on as02_cidadao        = ov02_sequencial";
    $sql .= "      left  join cidadaofamiliacadastrounico on as15_cidadaofamilia = as04_sequencial";
    $sql2 = "";
    if ($dbwhere=="") {
    	
      if ($as04_sequencial!=null ) {
        $sql2 .= " where cidadaofamilia.as04_sequencial              = $as04_sequencial";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
         
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
  function sql_query_familiarcadastrounico ( $as04_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql  .= " from cidadaofamilia ";
    $sql  .= "      left join cidadaofamiliacadastrounico on as15_cidadaofamilia = as04_sequencial ";
    $sql2  = "";
    
    if ($dbwhere == "") {
      
      if ($as04_sequencial != null ) {
        $sql2 .= " where cidadaofamilia.as04_sequencial = $as04_sequencial ";
      }
    } else if ($dbwhere != "") {
      
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }

  function sql_query_responsavel_por_resposta_avaliacao(array $aIdentificadoresResposta ) {
    
    if ( count($aIdentificadoresResposta) == 0 ) {
      return null;
    }
    $sIdentificadores = "'".implode("','", $aIdentificadoresResposta)."'";
    
    $sSql = "select pessoa_responsavel.as01_cidadao, cidadaocadastrounico.as02_sequencial                                                                                                                                                     ";
    $sSql.= "  from (select db108_avaliacaogruporesposta, avaliacaoperguntaopcao.db104_identificador, cidadaoavaliacao.as01_cidadao, as01_cidadao_seq                                          ";
    $sSql.= "          from avaliacaoresposta                                                                                                                                                              ";
    $sSql.= "               inner join avaliacaoperguntaopcao          on avaliacaoperguntaopcao.db104_sequencial                = avaliacaoresposta.db106_avaliacaoperguntaopcao                          ";
    $sSql.= "               inner join avaliacaogrupoperguntaresposta  on avaliacaogrupoperguntaresposta.db108_avaliacaoresposta = avaliacaoresposta.db106_sequencial                                      ";
    $sSql.= "               inner join avaliacaogruporesposta          on avaliacaogruporesposta.db107_sequencial                = avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta             ";
    $sSql.= "               inner join cidadaoavaliacao                on cidadaoavaliacao.as01_avaliacaogruporesposta           = avaliacaogruporesposta.db107_sequencial                                 ";
    $sSql.= "         where avaliacaoperguntaopcao.db104_identificador = 'PessoaResponsavel') as pessoa_responsavel                                                                                        ";
    $sSql.= "       inner join ( select db108_avaliacaogruporesposta, avaliacaoperguntaopcao.db104_identificador, cidadaoavaliacao.as01_cidadao, as01_cidadao_seq                                                         ";
    $sSql.= "                      from avaliacaoresposta                                                                                                                                                  ";
    $sSql.= "                           inner join avaliacaoperguntaopcao          on avaliacaoperguntaopcao.db104_sequencial                = avaliacaoresposta.db106_avaliacaoperguntaopcao              ";
    $sSql.= "                           inner join avaliacaogrupoperguntaresposta  on avaliacaogrupoperguntaresposta.db108_avaliacaoresposta = avaliacaoresposta.db106_sequencial                          ";
    $sSql.= "                           inner join avaliacaogruporesposta          on avaliacaogruporesposta.db107_sequencial                = avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta ";
    $sSql.= "                           inner join cidadaoavaliacao                on cidadaoavaliacao.as01_avaliacaogruporesposta           = avaliacaogruporesposta.db107_sequencial                     ";
    $sSql.= "                     where avaliacaoperguntaopcao.db104_identificador in ({$sIdentificadores})                                                                                                         ";
    $sSql.= "                  ) as tipo_trabalho on tipo_trabalho.as01_cidadao     = pessoa_responsavel.as01_cidadao                                                                                          ";
    $sSql.= "                                    and tipo_trabalho.as01_cidadao_seq = pessoa_responsavel.as01_cidadao_seq                                                                                          ";
    $sSql.= "       inner join cidadaocadastrounico on cidadaocadastrounico.as02_cidadao     = pessoa_responsavel.as01_cidadao ";
    $sSql.= "                                      and cidadaocadastrounico.as02_cidadao_seq = pessoa_responsavel.as01_cidadao_seq ";
    return $sSql;
  }
}
?>