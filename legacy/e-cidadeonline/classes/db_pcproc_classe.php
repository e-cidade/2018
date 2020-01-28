<?
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

//MODULO: compras
//CLASSE DA ENTIDADE pcproc
class cl_pcproc { 
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
   var $pc80_codproc = 0; 
   var $pc80_data_dia = null; 
   var $pc80_data_mes = null; 
   var $pc80_data_ano = null; 
   var $pc80_data = null; 
   var $pc80_usuario = 0; 
   var $pc80_depto = 0; 
   var $pc80_resumo = null; 
   var $pc80_situacao = 0; 
   var $pc80_tipoprocesso = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc80_codproc = int8 = Código do Processo de Compras 
                 pc80_data = date = Data do Processo de Compras 
                 pc80_usuario = int4 = Cod. Usuário 
                 pc80_depto = int4 = Departamento 
                 pc80_resumo = text = Resumo do Processo de Compras 
                 pc80_situacao = int4 = Situação 
                 pc80_tipoprocesso = int4 = Tipo de Processo 
                 ";
   //funcao construtor da classe 
   function cl_pcproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcproc"); 
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
       $this->pc80_codproc = ($this->pc80_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_codproc"]:$this->pc80_codproc);
       if($this->pc80_data == ""){
         $this->pc80_data_dia = ($this->pc80_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"]:$this->pc80_data_dia);
         $this->pc80_data_mes = ($this->pc80_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_data_mes"]:$this->pc80_data_mes);
         $this->pc80_data_ano = ($this->pc80_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_data_ano"]:$this->pc80_data_ano);
         if($this->pc80_data_dia != ""){
            $this->pc80_data = $this->pc80_data_ano."-".$this->pc80_data_mes."-".$this->pc80_data_dia;
         }
       }
       $this->pc80_usuario = ($this->pc80_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_usuario"]:$this->pc80_usuario);
       $this->pc80_depto = ($this->pc80_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_depto"]:$this->pc80_depto);
       $this->pc80_resumo = ($this->pc80_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_resumo"]:$this->pc80_resumo);
       $this->pc80_situacao = ($this->pc80_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_situacao"]:$this->pc80_situacao);
       $this->pc80_tipoprocesso = ($this->pc80_tipoprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"]:$this->pc80_tipoprocesso);
     }else{
       $this->pc80_codproc = ($this->pc80_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc80_codproc"]:$this->pc80_codproc);
     }
   }
   // funcao para inclusao
   function incluir ($pc80_codproc){ 
      $this->atualizacampos();
     if($this->pc80_data == null ){ 
       $this->erro_sql = " Campo Data do Processo de Compras não informado.";
       $this->erro_campo = "pc80_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc80_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "pc80_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc80_depto == null ){ 
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "pc80_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc80_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "pc80_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc80_tipoprocesso == null ){ 
       $this->pc80_tipoprocesso = "1";
     }
     if($pc80_codproc == "" || $pc80_codproc == null ){
       $result = db_query("select nextval('pcproc_pc80_codproc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcproc_pc80_codproc_seq do campo: pc80_codproc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc80_codproc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcproc_pc80_codproc_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc80_codproc)){
         $this->erro_sql = " Campo pc80_codproc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc80_codproc = $pc80_codproc; 
       }
     }
     if(($this->pc80_codproc == null) || ($this->pc80_codproc == "") ){ 
       $this->erro_sql = " Campo pc80_codproc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcproc(
                                       pc80_codproc 
                                      ,pc80_data 
                                      ,pc80_usuario 
                                      ,pc80_depto 
                                      ,pc80_resumo 
                                      ,pc80_situacao 
                                      ,pc80_tipoprocesso 
                       )
                values (
                                $this->pc80_codproc 
                               ,".($this->pc80_data == "null" || $this->pc80_data == ""?"null":"'".$this->pc80_data."'")." 
                               ,$this->pc80_usuario 
                               ,$this->pc80_depto 
                               ,'$this->pc80_resumo' 
                               ,$this->pc80_situacao 
                               ,$this->pc80_tipoprocesso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo de compras ($this->pc80_codproc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo de compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo de compras ($this->pc80_codproc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc80_codproc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc80_codproc  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6380,'$this->pc80_codproc','I')");
         $resac = db_query("insert into db_acount values($acount,1042,6380,'','".AddSlashes(pg_result($resaco,0,'pc80_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1042,6381,'','".AddSlashes(pg_result($resaco,0,'pc80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1042,6382,'','".AddSlashes(pg_result($resaco,0,'pc80_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1042,6383,'','".AddSlashes(pg_result($resaco,0,'pc80_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1042,6384,'','".AddSlashes(pg_result($resaco,0,'pc80_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1042,18603,'','".AddSlashes(pg_result($resaco,0,'pc80_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1042,20753,'','".AddSlashes(pg_result($resaco,0,'pc80_tipoprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($pc80_codproc=null) { 
      $this->atualizacampos();
     $sql = " update pcproc set ";
     $virgula = "";
     if(trim($this->pc80_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_codproc"])){ 
       $sql  .= $virgula." pc80_codproc = $this->pc80_codproc ";
       $virgula = ",";
       if(trim($this->pc80_codproc) == null ){ 
         $this->erro_sql = " Campo Código do Processo de Compras não informado.";
         $this->erro_campo = "pc80_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc80_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"] !="") ){ 
       $sql  .= $virgula." pc80_data = '$this->pc80_data' ";
       $virgula = ",";
       if(trim($this->pc80_data) == null ){ 
         $this->erro_sql = " Campo Data do Processo de Compras não informado.";
         $this->erro_campo = "pc80_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"])){ 
         $sql  .= $virgula." pc80_data = null ";
         $virgula = ",";
         if(trim($this->pc80_data) == null ){ 
           $this->erro_sql = " Campo Data do Processo de Compras não informado.";
           $this->erro_campo = "pc80_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc80_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_usuario"])){ 
       $sql  .= $virgula." pc80_usuario = $this->pc80_usuario ";
       $virgula = ",";
       if(trim($this->pc80_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "pc80_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc80_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_depto"])){ 
       $sql  .= $virgula." pc80_depto = $this->pc80_depto ";
       $virgula = ",";
       if(trim($this->pc80_depto) == null ){ 
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "pc80_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc80_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_resumo"])){ 
       $sql  .= $virgula." pc80_resumo = '$this->pc80_resumo' ";
       $virgula = ",";
     }
     if(trim($this->pc80_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_situacao"])){ 
       $sql  .= $virgula." pc80_situacao = $this->pc80_situacao ";
       $virgula = ",";
       if(trim($this->pc80_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "pc80_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc80_tipoprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"])){ 
        if(trim($this->pc80_tipoprocesso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"])){ 
           $this->pc80_tipoprocesso = "1" ; 
        } 
       $sql  .= $virgula." pc80_tipoprocesso = $this->pc80_tipoprocesso ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc80_codproc!=null){
       $sql .= " pc80_codproc = $this->pc80_codproc";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc80_codproc));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6380,'$this->pc80_codproc','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_codproc"]) || $this->pc80_codproc != "")
             $resac = db_query("insert into db_acount values($acount,1042,6380,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_codproc'))."','$this->pc80_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_data"]) || $this->pc80_data != "")
             $resac = db_query("insert into db_acount values($acount,1042,6381,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_data'))."','$this->pc80_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_usuario"]) || $this->pc80_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1042,6382,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_usuario'))."','$this->pc80_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_depto"]) || $this->pc80_depto != "")
             $resac = db_query("insert into db_acount values($acount,1042,6383,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_depto'))."','$this->pc80_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_resumo"]) || $this->pc80_resumo != "")
             $resac = db_query("insert into db_acount values($acount,1042,6384,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_resumo'))."','$this->pc80_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_situacao"]) || $this->pc80_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1042,18603,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_situacao'))."','$this->pc80_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"]) || $this->pc80_tipoprocesso != "")
             $resac = db_query("insert into db_acount values($acount,1042,20753,'".AddSlashes(pg_result($resaco,$conresaco,'pc80_tipoprocesso'))."','$this->pc80_tipoprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo de compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc80_codproc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo de compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc80_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc80_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($pc80_codproc=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc80_codproc));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6380,'$pc80_codproc','E')");
           $resac  = db_query("insert into db_acount values($acount,1042,6380,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1042,6381,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1042,6382,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1042,6383,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1042,6384,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1042,18603,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1042,20753,'','".AddSlashes(pg_result($resaco,$iresaco,'pc80_tipoprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pcproc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc80_codproc)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc80_codproc = $pc80_codproc ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo de compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc80_codproc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo de compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc80_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc80_codproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pcproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($pc80_codproc = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pcproc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codproc = pcproc.pc80_codproc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc80_codproc)) {
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pcproc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc80_codproc)){
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  function sql_query_autitem ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios          on db_usuarios.id_usuario              = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart            on db_depart.coddepto                  = pcproc.pc80_depto";
     $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codproc             = pcproc.pc80_codproc";
     $sql .= "      left  join acordopcprocitem     on pcprocitem.pc81_codprocitem         = acordopcprocitem.ac23_pcprocitem";
     $sql .= "      inner join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
     $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";    
     $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
     $sql .= "      left  join pcorcamitemproc      on pcorcamitemproc.pc31_pcprocitem     = pcprocitem.pc81_codprocitem";
     $sql .= "      left join liclicitem            on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc80_codproc!=null ){
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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

   function sql_query_aut ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart    on db_depart.coddepto     = pcproc.pc80_depto";
     $sql .= "      inner join pcprocitem   on pcproc.pc80_codproc    = pcprocitem.pc81_codproc";
     $sql .= "      left  join empautitem   on empautitem.e55_sequen  = pcprocitem.pc81_codprocitem ";
     $sql .= "      left  join empautoriza  on empautoriza.e54_autori = empautitem.e55_autori ";
     $sql .= "      left  join solicitem    on solicitem.pc11_codigo  = pcprocitem.pc81_solicitem ";
     $sql .= "      left  join solicita     on solicita.pc10_numero   = solicitem.pc11_numero ";
     $sql .= "      left  join solicitaregistropreco on solicita.pc10_numero  = solicitaregistropreco.pc54_solicita ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc80_codproc!=null ){
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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

  function sql_query_proc ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario              = pcproc.pc80_usuario ";
     $sql .= "      inner join db_depart             on db_depart.coddepto                  = pcproc.pc80_depto ";
     $sql .= "      inner join db_departorg          on db_departorg.db01_coddepto          = db_depart.coddepto ";
     $sql .= "                                      and db_departorg.db01_anousu            = " . db_getsession("DB_anousu");
     $sql .= "      inner join orcorgao              on orcorgao.o40_orgao                  = db_departorg.db01_orgao ";
     $sql .= "                                      and orcorgao.o40_anousu                 = db_departorg.db01_anousu ";
     $sql .= "      inner join pcprocitem            on pcprocitem.pc81_codproc             = pcproc.pc80_codproc ";
     $sql .= "      left  join solicitem             on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
     $sql .= "      left  join solicita              on solicita.pc10_numero                = solicitem.pc11_numero";
     $sql .= "      left  join liclicitem            on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem ";
     $sql .= "      left  join empautitempcprocitem  on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";    
     $sql .= "      left  join empautitem            on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                      and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      left  join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori "; 
     $sql .= "      left  join cgm                   on empautoriza.e54_numcgm              = cgm.z01_numcgm "; 
     $sql2 = "";
     if($dbwhere==""){
        if($pc80_codproc!=null ){
          $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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

  function sql_query_proc_solicita ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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
       $sql .= " from pcproc ";
       $sql .= "     inner join pcprocitem on pc81_codproc = pc80_codproc";
       $sql .= "     inner join db_depart  on pc80_depto   = coddepto";
       $sql .= "     inner join solicitem  on pc11_codigo  = pc81_solicitem";
       $sql .= "     inner join solicita   on pc10_numero = pc11_numero";
       $sql2 = "";
    if($dbwhere==""){
       if($pc80_codproc!=null ){
          $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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
  
  function sql_query_proc_and ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios    	    on db_usuarios.id_usuario              = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart      	    on db_depart.coddepto                  = pcproc.pc80_depto";
     $sql .= "      inner join db_departorg  	      on db_departorg.db01_coddepto          = db_depart.coddepto";
     $sql .= "                                     and db_departorg.db01_anousu 	         = " . db_getsession("DB_anousu");
     $sql .= "      inner join orcorgao             on orcorgao.o40_orgao                  = db_departorg.db01_orgao";
     $sql .= "                                     and orcorgao.o40_anousu 			           = db_departorg.db01_anousu";
     $sql .= "      inner join pcprocitem     	    on pcprocitem.pc81_codproc             = pcproc.pc80_codproc";
     $sql .= "      left  join acordopcprocitem     on pcprocitem.pc81_codprocitem         = acordopcprocitem.ac23_pcprocitem";
     $sql .= "      left  join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
	   $sql .= "      left  join solicitemprot        on solicitemprot.pc49_solicitem        = solicitem.pc11_codigo";
	   $sql .= "      left  join proctransferproc     on proctransferproc.p63_codproc        = solicitemprot.pc49_protprocesso";
	   $sql .= "      left  join proctransfer         on proctransfer.p62_codtran            = proctransferproc.p63_codtran";
	   $sql .= "      left  join proctransand         on proctransand.p64_codtran            = proctransfer.p62_codtran";
     $sql .= "      left  join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
     $sql .= "      left  join liclicitem           on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem";
     $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";    
     $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori"; 
     $sql2 = "";
     if($dbwhere==""){
        if($pc80_codproc!=null ){
          $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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

    
  function sql_query_usudepart ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc80_codproc!=null ){
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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

   function sql_query_soland( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
		 $sql .= " from pcproc                                                                                                      ";
		 $sql .= "      inner join pcprocitem            on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc            ";
		 $sql .= "      left  join solicitem             on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem          ";
	   $sql .= "      left  join solicita              on solicita.pc10_numero                = solicitem.pc11_numero              ";
     $sql .= "      left  join solicitaregistropreco on solicita.pc10_numero  = solicitaregistropreco.pc54_solicita ";
     $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem        ";
     $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori    ";
     $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen    ";
		 $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori              ";
		 $sql .= "      inner join solandam             on solandam.pc43_solicitem             = pcprocitem.pc81_solicitem          ";
		 $sql .= "      inner join ( select max(pc43_codigo) as codigo,                                                             ";
		 $sql .= "                          pc43_solicitem                                                                          ";
		 $sql .= "                     from solandam                                                                                ";
		 $sql .= "                 group by pc43_solicitem  )  as  x  on x.codigo = pc43_codigo                                     ";
     $sql2 = "";
     
     if($dbwhere==""){
       if($pc80_codproc!=null ){
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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
   
   function sql_query_leftprocitem($pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
   $sql .= "      from pcproc                                                                           ";
   $sql .= "           left join pcprocitem  on pcproc.pc80_codproc = pcprocitem.pc81_codproc      ";
   $sql .= "           left join solicitem   on pc81_solicitem      = pc11_codigo      ";
     
     $sql2 = "";
     
     if($dbwhere==""){
       if($pc80_codproc!=null ){
         $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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

    
  public function sql_query_gerautproc($pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
    
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
     
    $sql .= "  from pcproc ";
    $sql .= "       inner join pcprocitem           on pcprocitem.pc81_codproc = pcproc.pc80_codproc";
    $sql .= "       inner join solicitem            on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
    $sql .= "       inner join solicita             on solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "       inner join solicitempcmater     on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "       inner join pcmater              on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "       inner join solicitemele         on solicitemele.pc18_solicitem = solicitem.pc11_codigo";
    $sql .= "       left  join solicitemunid        on solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "       left  join matunid              on matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "       inner join pcdotac              on pcdotac.pc13_codigo = solicitem.pc11_codigo";
    $sql .= "       left  join pcdotaccontrapartida on pcdotaccontrapartida.pc19_pcdotac = pcdotac.pc13_sequencial";
    $sql .= "       inner join orcdotacao           on orcdotacao.o58_anousu = pcdotac.pc13_anousu";
    $sql .= "                                      and orcdotacao.o58_coddot = pcdotac.pc13_coddot";
    $sql .= "       left  join pcorcamitemproc      on pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "       left  join orcreservasol        on orcreservasol.o82_pcdotac = pcdotac.pc13_sequencial";
    $sql .= "       left  join orcreserva           on orcreserva.o80_codres = orcreservasol.o82_codres";
    $sql .= "       left  join pcorcamitem          on pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
    $sql .= "       left  join pcorcam              on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
    $sql .= "       left  join pcorcamforne         on pcorcamforne.pc21_codorc = pcorcam.pc20_codorc";
    $sql .= "       left  join cgm                  on cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
    $sql .= "       left  join pcorcamval           on pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne";
    $sql .= "                                      and pcorcamval.pc23_orcamitem  = pcorcamitem.pc22_orcamitem";
    $sql .= "       left  join pcorcamjulg          on pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne";
    $sql .= "                                      and pcorcamjulg.pc24_orcamitem = pcorcamitem.pc22_orcamitem";
   //$sql .= "                                      and pc24_pontuacao = 1 ";
    $sql .= "       left  join orcelemento          on orcelemento.o56_codele = solicitemele.pc18_codele";
    $sql .= "                                      and o56_anousu = ".db_getsession("DB_anousu");
    $sql2 = "";
    if($dbwhere==""){
      if($pc80_codproc!=null ){
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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

  public function sql_query_dados_item($pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
    
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
     
    $sql .= "  from pcproc 																																														";
    $sql .= "       inner join pcprocitem           on pcprocitem.pc81_codproc            = pcproc.pc80_codproc       ";
    $sql .= "       inner join solicitem            on solicitem.pc11_codigo              = pcprocitem.pc81_solicitem ";
    $sql .= "       inner join solicita             on solicita.pc10_numero               = solicitem.pc11_numero     ";
    $sql .= "       inner join solicitempcmater     on solicitempcmater.pc16_solicitem    = solicitem.pc11_codigo     ";
    $sql .= "       inner join pcmater              on pcmater.pc01_codmater              = solicitempcmater.pc16_codmater ";
    $sql .= "       left join solicitemele          on solicitemele.pc18_solicitem        = solicitem.pc11_codigo     ";
    $sql .= "       left  join solicitemunid        on solicitemunid.pc17_codigo          = solicitem.pc11_codigo     ";
    $sql .= "       left  join matunid              on matunid.m61_codmatunid             = solicitemunid.pc17_unid   ";
    $sql .= "       left  join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita = solicita.pc10_numero      ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc80_codproc!=null ){
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc "; 
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
  
  public function sql_query_dados_licitacao($pc80_codproc=null,$campos="*",$ordem=null,$dbwhere="") {
    
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario      = pcproc.pc80_usuario";
     $sql .= "      inner join db_depart    on db_depart.coddepto          = pcproc.pc80_depto";
     $sql .= "      inner join pcprocitem   on pcprocitem.pc81_codproc     = pcproc.pc80_codproc";
     $sql .= "      inner join liclicitem   on pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita    on liclicitem.l21_codliclicita = liclicita.l20_codigo";
     $sql .= "      inner join cflicita     on cflicita.l03_codigo         = liclicita.l20_codtipocom";
     $sql2 = "";
     if($dbwhere==""){
        if($pc80_codproc!=null ){
          $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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
    
    
  function sql_query_empenho ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcproc ";
     $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario              = pcproc.pc80_usuario ";
     $sql .= "      inner join db_depart             on db_depart.coddepto                  = pcproc.pc80_depto ";
     $sql .= "      inner join db_departorg          on db_departorg.db01_coddepto          = db_depart.coddepto ";
     $sql .= "                                      and db_departorg.db01_anousu            = " . db_getsession("DB_anousu");
     $sql .= "      inner join orcorgao              on orcorgao.o40_orgao                  = db_departorg.db01_orgao ";
     $sql .= "                                      and orcorgao.o40_anousu                 = db_departorg.db01_anousu ";
     $sql .= "      inner join pcprocitem            on pcprocitem.pc81_codproc             = pcproc.pc80_codproc ";
     $sql .= "      inner  join solicitem             on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
     $sql .= "      inner  join solicita              on solicita.pc10_numero                = solicitem.pc11_numero";
     $sql .= "      inner  join empautitempcprocitem  on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";    
     $sql .= "      inner  join empautitem            on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
     $sql .= "                                      and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
     $sql .= "      inner  join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori "; 
     $sql .= "      inner  join empempaut             on empautoriza.e54_autori              = empempaut.e61_autori ";
     $sql .= "      inner  join empempenho            on empempenho.e60_numemp               = empempaut.e61_numemp ";
     $sql .= "      inner  join cgm                   on empempenho.e60_numcgm               = cgm.z01_numcgm "; 
     $sql2 = "";
     if($dbwhere==""){
        if($pc80_codproc!=null ){
          $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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

  function sql_query_tipocompra ($pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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
    	$sql .= " from pcproc ";
    	$sql .= "      inner join pcprocitem   on pcprocitem.pc81_codproc = pcproc.pc80_codproc";
    	$sql .= "      inner join solicitem    on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
    	$sql .= "      inner join solicita     on solicita.pc10_numero = solicitem.pc11_numero";
    	$sql .= "      inner join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
    	$sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
    	
    	
    	$sql2 = "";
    	if($dbwhere==""){
    		if($pc80_codproc!=null ){
    			$sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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
   
   
   
   function sql_query_proc_solicita_abertura ( $pc80_codproc=null,$campos="*",$ordem=null,$dbwhere=""){
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
      $sql .= " from pcproc ";
      $sql .= "     inner join pcprocitem on pc81_codproc = pc80_codproc";
      $sql .= "     inner join db_depart  on pc80_depto   = coddepto";
      $sql .= "     inner join solicitem  on pc11_codigo  = pc81_solicitem";
      $sql .= "     inner join solicita  compilacao on compilacao.pc10_numero = pc11_numero and compilacao.pc10_solicitacaotipo = 6";
      $sql .= "     inner join solicitavinculo  on compilacao.pc10_numero = pc53_solicitafilho";
      $sql .= "     inner join solicita abertura on abertura.pc10_numero  = pc53_solicitapai";
      $sql2 = "";
      if($dbwhere==""){
        if($pc80_codproc!=null ){
          $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
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
