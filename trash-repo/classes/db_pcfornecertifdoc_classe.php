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

//MODULO: Compras
//CLASSE DA ENTIDADE pcfornecertifdoc
class cl_pcfornecertifdoc { 
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
   var $pc75_codigo = 0; 
   var $pc75_pcfornecertif = 0; 
   var $pc75_pcdoccertif = 0; 
   var $pc75_validade_dia = null; 
   var $pc75_validade_mes = null; 
   var $pc75_validade_ano = null; 
   var $pc75_validade = null; 
   var $pc75_obrigatorio = 'f'; 
   var $pc75_obs = null; 
   var $pc75_apresentado = 0; 
   var $pc75_dataemissao_dia = null; 
   var $pc75_dataemissao_mes = null; 
   var $pc75_dataemissao_ano = null; 
   var $pc75_dataemissao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc75_codigo = int4 = Código 
                 pc75_pcfornecertif = int4 = Código 
                 pc75_pcdoccertif = int4 = Cod. Documento 
                 pc75_validade = date = Validade do Documento 
                 pc75_obrigatorio = bool = Obrigatório 
                 pc75_obs = text = Observação 
                 pc75_apresentado = int4 = Apresentado 
                 pc75_dataemissao = date = Data de Emissão 
                 ";
   //funcao construtor da classe 
   function cl_pcfornecertifdoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornecertifdoc"); 
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
       $this->pc75_codigo = ($this->pc75_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_codigo"]:$this->pc75_codigo);
       $this->pc75_pcfornecertif = ($this->pc75_pcfornecertif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_pcfornecertif"]:$this->pc75_pcfornecertif);
       $this->pc75_pcdoccertif = ($this->pc75_pcdoccertif == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_pcdoccertif"]:$this->pc75_pcdoccertif);
       if($this->pc75_validade == ""){
         $this->pc75_validade_dia = ($this->pc75_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_validade_dia"]:$this->pc75_validade_dia);
         $this->pc75_validade_mes = ($this->pc75_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_validade_mes"]:$this->pc75_validade_mes);
         $this->pc75_validade_ano = ($this->pc75_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_validade_ano"]:$this->pc75_validade_ano);
         if($this->pc75_validade_dia != ""){
            $this->pc75_validade = $this->pc75_validade_ano."-".$this->pc75_validade_mes."-".$this->pc75_validade_dia;
         }
       }
       $this->pc75_obrigatorio = ($this->pc75_obrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc75_obrigatorio"]:$this->pc75_obrigatorio);
       $this->pc75_obs = ($this->pc75_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_obs"]:$this->pc75_obs);
       $this->pc75_apresentado = ($this->pc75_apresentado == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_apresentado"]:$this->pc75_apresentado);
       if($this->pc75_dataemissao == ""){
         $this->pc75_dataemissao_dia = ($this->pc75_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao_dia"]:$this->pc75_dataemissao_dia);
         $this->pc75_dataemissao_mes = ($this->pc75_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao_mes"]:$this->pc75_dataemissao_mes);
         $this->pc75_dataemissao_ano = ($this->pc75_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao_ano"]:$this->pc75_dataemissao_ano);
         if($this->pc75_dataemissao_dia != ""){
            $this->pc75_dataemissao = $this->pc75_dataemissao_ano."-".$this->pc75_dataemissao_mes."-".$this->pc75_dataemissao_dia;
         }
       }
     }else{
       $this->pc75_codigo = ($this->pc75_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc75_codigo"]:$this->pc75_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc75_codigo){ 
      $this->atualizacampos();
     if($this->pc75_pcfornecertif == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "pc75_pcfornecertif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc75_pcdoccertif == null ){ 
       $this->erro_sql = " Campo Cod. Documento nao Informado.";
       $this->erro_campo = "pc75_pcdoccertif";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc75_validade == null ){ 
       $this->erro_sql = " Campo Validade do Documento nao Informado.";
       $this->erro_campo = "pc75_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc75_obrigatorio == null ){ 
       $this->erro_sql = " Campo Obrigatório nao Informado.";
       $this->erro_campo = "pc75_obrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc75_apresentado == null ){ 
       $this->erro_sql = " Campo Apresentado nao Informado.";
       $this->erro_campo = "pc75_apresentado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc75_dataemissao == null ){ 
       $this->pc75_dataemissao = "null";
     }
     if($pc75_codigo == "" || $pc75_codigo == null ){
       $result = db_query("select nextval('pcfornecertifdoc_pc75_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfornecertifdoc_pc75_codigo_seq do campo: pc75_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc75_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfornecertifdoc_pc75_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc75_codigo)){
         $this->erro_sql = " Campo pc75_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc75_codigo = $pc75_codigo; 
       }
     }
     if(($this->pc75_codigo == null) || ($this->pc75_codigo == "") ){ 
       $this->erro_sql = " Campo pc75_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornecertifdoc(
                                       pc75_codigo 
                                      ,pc75_pcfornecertif 
                                      ,pc75_pcdoccertif 
                                      ,pc75_validade 
                                      ,pc75_obrigatorio 
                                      ,pc75_obs 
                                      ,pc75_apresentado 
                                      ,pc75_dataemissao 
                       )
                values (
                                $this->pc75_codigo 
                               ,$this->pc75_pcfornecertif 
                               ,$this->pc75_pcdoccertif 
                               ,".($this->pc75_validade == "null" || $this->pc75_validade == ""?"null":"'".$this->pc75_validade."'")." 
                               ,'$this->pc75_obrigatorio' 
                               ,'$this->pc75_obs' 
                               ,$this->pc75_apresentado 
                               ,".($this->pc75_dataemissao == "null" || $this->pc75_dataemissao == ""?"null":"'".$this->pc75_dataemissao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "documentos do certificado do fornecedor ($this->pc75_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "documentos do certificado do fornecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "documentos do certificado do fornecedor ($this->pc75_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc75_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc75_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7802,'$this->pc75_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1306,7802,'','".AddSlashes(pg_result($resaco,0,'pc75_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,7803,'','".AddSlashes(pg_result($resaco,0,'pc75_pcfornecertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,7804,'','".AddSlashes(pg_result($resaco,0,'pc75_pcdoccertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,7805,'','".AddSlashes(pg_result($resaco,0,'pc75_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,7806,'','".AddSlashes(pg_result($resaco,0,'pc75_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,7807,'','".AddSlashes(pg_result($resaco,0,'pc75_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,16556,'','".AddSlashes(pg_result($resaco,0,'pc75_apresentado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1306,16557,'','".AddSlashes(pg_result($resaco,0,'pc75_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc75_codigo=null) { 
      $this->atualizacampos();
     $sql = " update pcfornecertifdoc set ";
     $virgula = "";
     if(trim($this->pc75_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_codigo"])){ 
       $sql  .= $virgula." pc75_codigo = $this->pc75_codigo ";
       $virgula = ",";
       if(trim($this->pc75_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc75_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc75_pcfornecertif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_pcfornecertif"])){ 
       $sql  .= $virgula." pc75_pcfornecertif = $this->pc75_pcfornecertif ";
       $virgula = ",";
       if(trim($this->pc75_pcfornecertif) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc75_pcfornecertif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc75_pcdoccertif)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_pcdoccertif"])){ 
       $sql  .= $virgula." pc75_pcdoccertif = $this->pc75_pcdoccertif ";
       $virgula = ",";
       if(trim($this->pc75_pcdoccertif) == null ){ 
         $this->erro_sql = " Campo Cod. Documento nao Informado.";
         $this->erro_campo = "pc75_pcdoccertif";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc75_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc75_validade_dia"] !="") ){ 
       $sql  .= $virgula." pc75_validade = '$this->pc75_validade' ";
       $virgula = ",";
     } else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_validade_dia"]) || trim($this->pc75_validade) == ""){ 
         $sql  .= $virgula." pc75_validade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc75_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_obrigatorio"])){ 
       $sql  .= $virgula." pc75_obrigatorio = '$this->pc75_obrigatorio' ";
       $virgula = ",";
       if(trim($this->pc75_obrigatorio) == null ){ 
         $this->erro_sql = " Campo Obrigatório nao Informado.";
         $this->erro_campo = "pc75_obrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     if(trim($this->pc75_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_obs"])){ 
       if(trim($this->pc75_obs) == "null"){
       	$this->pc75_obs = '';
       }
     	 $sql  .= $virgula." pc75_obs = '$this->pc75_obs' ";
       $virgula = ",";
     }
     
     if(trim($this->pc75_apresentado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_apresentado"])){ 
       $sql  .= $virgula." pc75_apresentado = $this->pc75_apresentado ";
       $virgula = ",";
       if(trim($this->pc75_apresentado) == null ){ 
         $this->erro_sql = " Campo Apresentado nao Informado.";
         $this->erro_campo = "pc75_apresentado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc75_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao_dia"] !="") ){ 
       $sql  .= $virgula." pc75_dataemissao = '$this->pc75_dataemissao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao_dia"]) || $this->pc75_dataemissao == null){ 
         $sql  .= $virgula." pc75_dataemissao = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($pc75_codigo!=null){
       $sql .= " pc75_codigo = $this->pc75_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc75_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7802,'$this->pc75_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_codigo"]) || $this->pc75_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1306,7802,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_codigo'))."','$this->pc75_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_pcfornecertif"]) || $this->pc75_pcfornecertif != "")
           $resac = db_query("insert into db_acount values($acount,1306,7803,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_pcfornecertif'))."','$this->pc75_pcfornecertif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_pcdoccertif"]) || $this->pc75_pcdoccertif != "")
           $resac = db_query("insert into db_acount values($acount,1306,7804,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_pcdoccertif'))."','$this->pc75_pcdoccertif',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_validade"]) || $this->pc75_validade != "")
           $resac = db_query("insert into db_acount values($acount,1306,7805,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_validade'))."','$this->pc75_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_obrigatorio"]) || $this->pc75_obrigatorio != "")
           $resac = db_query("insert into db_acount values($acount,1306,7806,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_obrigatorio'))."','$this->pc75_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_obs"]) || $this->pc75_obs != "")
           $resac = db_query("insert into db_acount values($acount,1306,7807,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_obs'))."','$this->pc75_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_apresentado"]) || $this->pc75_apresentado != "")
           $resac = db_query("insert into db_acount values($acount,1306,16556,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_apresentado'))."','$this->pc75_apresentado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc75_dataemissao"]) || $this->pc75_dataemissao != "")
           $resac = db_query("insert into db_acount values($acount,1306,16557,'".AddSlashes(pg_result($resaco,$conresaco,'pc75_dataemissao'))."','$this->pc75_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "documentos do certificado do fornecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc75_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "documentos do certificado do fornecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc75_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc75_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7802,'$pc75_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1306,7802,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,7803,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_pcfornecertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,7804,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_pcdoccertif'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,7805,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,7806,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,7807,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,16556,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_apresentado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1306,16557,'','".AddSlashes(pg_result($resaco,$iresaco,'pc75_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornecertifdoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc75_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc75_codigo = $pc75_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "documentos do certificado do fornecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc75_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "documentos do certificado do fornecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc75_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornecertifdoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc75_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecertifdoc ";
     $sql .= "      inner join pcdoccertif  on  pcdoccertif.pc71_codigo = pcfornecertifdoc.pc75_pcdoccertif";
     $sql .= "      inner join pcfornecertif  on  pcfornecertif.pc74_codigo = pcfornecertifdoc.pc75_pcfornecertif";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcfornecertif.pc74_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = pcfornecertif.pc74_coddepto";
     $sql .= "      inner join pcforne  as a on   a.pc60_numcgm = pcfornecertif.pc74_pcforne";
     $sql .= "      inner join pctipocertif  on  pctipocertif.pc70_codigo = pcfornecertif.pc74_pctipocertif";
     $sql2 = "";
     if($dbwhere==""){
       if($pc75_codigo!=null ){
         $sql2 .= " where pcfornecertifdoc.pc75_codigo = $pc75_codigo "; 
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
   function sql_query_file ( $pc75_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornecertifdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc75_codigo!=null ){
         $sql2 .= " where pcfornecertifdoc.pc75_codigo = $pc75_codigo "; 
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