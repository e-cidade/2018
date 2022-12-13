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
//CLASSE DA ENTIDADE cidadaocadastrounico
class cl_cidadaocadastrounico { 
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
   var $as02_sequencial = 0; 
   var $as02_cidadao = 0; 
   var $as02_cidadao_seq = 0; 
   var $as02_nis = null; 
   var $as02_apelido = null; 
   var $as02_dataatualizacao_dia = null; 
   var $as02_dataatualizacao_mes = null; 
   var $as02_dataatualizacao_ano = null; 
   var $as02_dataatualizacao = null; 
   var $as02_codigounicocidadao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as02_sequencial = int4 = Código 
                 as02_cidadao = int4 = Cidadão 
                 as02_cidadao_seq = int4 = Código Cidadão 
                 as02_nis = varchar(20) = NIS 
                 as02_apelido = varchar(50) = Apelido 
                 as02_dataatualizacao = date = Data de Atualização 
                 as02_codigounicocidadao = varchar(20) = Código Único Cidadão 
                 ";
   //funcao construtor da classe 
   function cl_cidadaocadastrounico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaocadastrounico"); 
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
       $this->as02_sequencial = ($this->as02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_sequencial"]:$this->as02_sequencial);
       $this->as02_cidadao = ($this->as02_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_cidadao"]:$this->as02_cidadao);
       $this->as02_cidadao_seq = ($this->as02_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_cidadao_seq"]:$this->as02_cidadao_seq);
       $this->as02_nis = ($this->as02_nis == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_nis"]:$this->as02_nis);
       $this->as02_apelido = ($this->as02_apelido == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_apelido"]:$this->as02_apelido);
       if($this->as02_dataatualizacao == ""){
         $this->as02_dataatualizacao_dia = ($this->as02_dataatualizacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao_dia"]:$this->as02_dataatualizacao_dia);
         $this->as02_dataatualizacao_mes = ($this->as02_dataatualizacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao_mes"]:$this->as02_dataatualizacao_mes);
         $this->as02_dataatualizacao_ano = ($this->as02_dataatualizacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao_ano"]:$this->as02_dataatualizacao_ano);
         if($this->as02_dataatualizacao_dia != ""){
            $this->as02_dataatualizacao = $this->as02_dataatualizacao_ano."-".$this->as02_dataatualizacao_mes."-".$this->as02_dataatualizacao_dia;
         }
       }
       $this->as02_codigounicocidadao = ($this->as02_codigounicocidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_codigounicocidadao"]:$this->as02_codigounicocidadao);
     }else{
       $this->as02_sequencial = ($this->as02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as02_sequencial"]:$this->as02_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as02_sequencial){ 
      $this->atualizacampos();
     if($this->as02_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "as02_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as02_cidadao_seq == null ){ 
       $this->erro_sql = " Campo Código Cidadão nao Informado.";
       $this->erro_campo = "as02_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as02_nis == null ){ 
       $this->erro_sql = " Campo NIS nao Informado.";
       $this->erro_campo = "as02_nis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as02_dataatualizacao == null ){ 
       $this->as02_dataatualizacao = "null";
     }
     if($this->as02_codigounicocidadao == null ){ 
       $this->erro_sql = " Campo Código Único Cidadão nao Informado.";
       $this->erro_campo = "as02_codigounicocidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as02_sequencial == "" || $as02_sequencial == null ){
       $result = db_query("select nextval('cidadaocadastrounico_as02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaocadastrounico_as02_sequencial_seq do campo: as02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaocadastrounico_as02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as02_sequencial)){
         $this->erro_sql = " Campo as02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as02_sequencial = $as02_sequencial; 
       }
     }
     if(($this->as02_sequencial == null) || ($this->as02_sequencial == "") ){ 
       $this->erro_sql = " Campo as02_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaocadastrounico(
                                       as02_sequencial 
                                      ,as02_cidadao 
                                      ,as02_cidadao_seq 
                                      ,as02_nis 
                                      ,as02_apelido 
                                      ,as02_dataatualizacao 
                                      ,as02_codigounicocidadao 
                       )
                values (
                                $this->as02_sequencial 
                               ,$this->as02_cidadao 
                               ,$this->as02_cidadao_seq 
                               ,'$this->as02_nis' 
                               ,'$this->as02_apelido' 
                               ,".($this->as02_dataatualizacao == "null" || $this->as02_dataatualizacao == ""?"null":"'".$this->as02_dataatualizacao."'")." 
                               ,'$this->as02_codigounicocidadao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaocadastrounico ($this->as02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaocadastrounico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaocadastrounico ($this->as02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as02_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19070,'$this->as02_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3392,19070,'','".AddSlashes(pg_result($resaco,0,'as02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3392,19071,'','".AddSlashes(pg_result($resaco,0,'as02_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3392,19097,'','".AddSlashes(pg_result($resaco,0,'as02_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3392,19072,'','".AddSlashes(pg_result($resaco,0,'as02_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3392,19073,'','".AddSlashes(pg_result($resaco,0,'as02_apelido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3392,19112,'','".AddSlashes(pg_result($resaco,0,'as02_dataatualizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3392,19172,'','".AddSlashes(pg_result($resaco,0,'as02_codigounicocidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as02_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaocadastrounico set ";
     $virgula = "";
     if(trim($this->as02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_sequencial"])){ 
       $sql  .= $virgula." as02_sequencial = $this->as02_sequencial ";
       $virgula = ",";
       if(trim($this->as02_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as02_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_cidadao"])){ 
       $sql  .= $virgula." as02_cidadao = $this->as02_cidadao ";
       $virgula = ",";
       if(trim($this->as02_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "as02_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as02_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_cidadao_seq"])){ 
       $sql  .= $virgula." as02_cidadao_seq = $this->as02_cidadao_seq ";
       $virgula = ",";
       if(trim($this->as02_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo Código Cidadão nao Informado.";
         $this->erro_campo = "as02_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as02_nis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_nis"])){ 
       $sql  .= $virgula." as02_nis = '$this->as02_nis' ";
       $virgula = ",";
       if(trim($this->as02_nis) == null ){ 
         $this->erro_sql = " Campo NIS nao Informado.";
         $this->erro_campo = "as02_nis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as02_apelido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_apelido"])){ 
       $sql  .= $virgula." as02_apelido = '$this->as02_apelido' ";
       $virgula = ",";
     }
     if(trim($this->as02_dataatualizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao_dia"] !="") ){ 
       $sql  .= $virgula." as02_dataatualizacao = '$this->as02_dataatualizacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao_dia"])){ 
         $sql  .= $virgula." as02_dataatualizacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->as02_codigounicocidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as02_codigounicocidadao"])){ 
       $sql  .= $virgula." as02_codigounicocidadao = '$this->as02_codigounicocidadao' ";
       $virgula = ",";
       if(trim($this->as02_codigounicocidadao) == null ){ 
         $this->erro_sql = " Campo Código Único Cidadão nao Informado.";
         $this->erro_campo = "as02_codigounicocidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as02_sequencial!=null){
       $sql .= " as02_sequencial = $this->as02_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as02_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19070,'$this->as02_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_sequencial"]) || $this->as02_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3392,19070,'".AddSlashes(pg_result($resaco,$conresaco,'as02_sequencial'))."','$this->as02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_cidadao"]) || $this->as02_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3392,19071,'".AddSlashes(pg_result($resaco,$conresaco,'as02_cidadao'))."','$this->as02_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_cidadao_seq"]) || $this->as02_cidadao_seq != "")
             $resac = db_query("insert into db_acount values($acount,3392,19097,'".AddSlashes(pg_result($resaco,$conresaco,'as02_cidadao_seq'))."','$this->as02_cidadao_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_nis"]) || $this->as02_nis != "")
             $resac = db_query("insert into db_acount values($acount,3392,19072,'".AddSlashes(pg_result($resaco,$conresaco,'as02_nis'))."','$this->as02_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_apelido"]) || $this->as02_apelido != "")
             $resac = db_query("insert into db_acount values($acount,3392,19073,'".AddSlashes(pg_result($resaco,$conresaco,'as02_apelido'))."','$this->as02_apelido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_dataatualizacao"]) || $this->as02_dataatualizacao != "")
             $resac = db_query("insert into db_acount values($acount,3392,19112,'".AddSlashes(pg_result($resaco,$conresaco,'as02_dataatualizacao'))."','$this->as02_dataatualizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as02_codigounicocidadao"]) || $this->as02_codigounicocidadao != "")
             $resac = db_query("insert into db_acount values($acount,3392,19172,'".AddSlashes(pg_result($resaco,$conresaco,'as02_codigounicocidadao'))."','$this->as02_codigounicocidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaocadastrounico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaocadastrounico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as02_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as02_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19070,'$as02_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3392,19070,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3392,19071,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3392,19097,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3392,19072,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3392,19073,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_apelido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3392,19112,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_dataatualizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3392,19172,'','".AddSlashes(pg_result($resaco,$iresaco,'as02_codigounicocidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaocadastrounico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as02_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as02_sequencial = $as02_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaocadastrounico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaocadastrounico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaocadastrounico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaocadastrounico ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cidadaocadastrounico.as02_cidadao and  cidadao.ov02_seq = cidadaocadastrounico.as02_cidadao_seq";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql2 = "";
     if($dbwhere==""){
       if($as02_sequencial!=null ){
         $sql2 .= " where cidadaocadastrounico.as02_sequencial = $as02_sequencial "; 
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
   function sql_query_file ( $as02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaocadastrounico ";
     $sql2 = "";
     if($dbwhere==""){
       if($as02_sequencial!=null ){
         $sql2 .= " where cidadaocadastrounico.as02_sequencial = $as02_sequencial "; 
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
   function sql_query_cidadao_avalicao ($as02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
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
    $sql .= " from cidadaocadastrounico ";
    $sql .= "      inner join cidadao           on  cidadao.ov02_sequencial = cidadaocadastrounico.as02_cidadao ";
    $sql .= "                                  and  cidadao.ov02_seq        = cidadaocadastrounico.as02_cidadao_seq";
    $sql .= "       left  join cidadaoavaliacao on as01_cidadao             = cidadao.ov02_sequencial ";
    $sql .= "                                  and as01_cidadao_seq         = cidadao.ov02_seq";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if($as02_sequencial!=null) {
        $sql2 .= " where cidadaocadastrounico.as02_sequencial = $as02_sequencial ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_cidadaofamiliaresponsavel ( $as04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadaocadastrounico ";
    $sql .= "      inner join cidadao                    on ov02_sequencial  = as02_cidadao";
    $sql .= "                                           and ov02_seq         = as02_cidadao_seq";
    $sql .= "      inner join cidadaocomposicaofamiliar  on as03_cidadao     = ov02_sequencial";
    $sql .= "                                           and as03_cidadao_seq = ov02_seq";
    $sql .= "      inner join cidadaofamilia             on as04_sequencial  = as03_cidadaofamilia";
    $sql2 = "";
    if ($dbwhere=="") {
       
      if ($as04_sequencial!=null ) {
        $sql2 .= " where cidadaofamilia.as04_sequencial              = $as04_sequencial";
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