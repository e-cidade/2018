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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_alimento
class cl_mer_alimento { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me35_i_codigo        = 0; 
   var $me35_c_nomealimento        = null; 
   var $me35_c_nomecientifico        = null; 
   var $me35_i_grupoalimentar        = 0; 
   var $me35_c_fonteinformacao        = null; 
   var $me35_i_unidade        = 0; 
   var $me35_c_quant        = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me35_i_codigo = int4 = C�digo 
                 me35_c_nomealimento = char(100) = Alimento 
                 me35_c_nomecientifico = char(100) = Nome Cient�fico 
                 me35_i_grupoalimentar = int4 = Grupo alimentar 
                 me35_c_fonteinformacao = char(100) = Fonte da Informa��o 
                 me35_i_unidade = int4 = Unidade 
                 me35_c_quant = char(5) = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_mer_alimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_alimento"); 
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
       $this->me35_i_codigo = ($this->me35_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_i_codigo"]:$this->me35_i_codigo);
       $this->me35_c_nomealimento = ($this->me35_c_nomealimento == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_c_nomealimento"]:$this->me35_c_nomealimento);
       $this->me35_c_nomecientifico = ($this->me35_c_nomecientifico == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_c_nomecientifico"]:$this->me35_c_nomecientifico);
       $this->me35_i_grupoalimentar = ($this->me35_i_grupoalimentar == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_i_grupoalimentar"]:$this->me35_i_grupoalimentar);
       $this->me35_c_fonteinformacao = ($this->me35_c_fonteinformacao == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_c_fonteinformacao"]:$this->me35_c_fonteinformacao);
       $this->me35_i_unidade = ($this->me35_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_i_unidade"]:$this->me35_i_unidade);
       $this->me35_c_quant = ($this->me35_c_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_c_quant"]:$this->me35_c_quant);
     }else{
       $this->me35_i_codigo = ($this->me35_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me35_i_codigo"]:$this->me35_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me35_i_codigo){ 
      $this->atualizacampos();
     if($this->me35_c_nomealimento == null ){ 
       $this->erro_sql = " Campo Alimento nao Informado.";
       $this->erro_campo = "me35_c_nomealimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me35_c_nomecientifico == null ){ 
       $this->erro_sql = " Campo Nome Cient�fico nao Informado.";
       $this->erro_campo = "me35_c_nomecientifico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me35_i_grupoalimentar == null ){ 
       $this->erro_sql = " Campo Grupo alimentar nao Informado.";
       $this->erro_campo = "me35_i_grupoalimentar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me35_c_fonteinformacao == null ){ 
       $this->erro_sql = " Campo Fonte da Informa��o nao Informado.";
       $this->erro_campo = "me35_c_fonteinformacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me35_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "me35_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me35_c_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "me35_c_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me35_i_codigo == "" || $me35_i_codigo == null ){
       $result = db_query("select nextval('mer_alimento_me35_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_alimento_me35_i_codigo_seq do campo: me35_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me35_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_alimento_me35_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me35_i_codigo)){
         $this->erro_sql = " Campo me35_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me35_i_codigo = $me35_i_codigo; 
       }
     }
     if(($this->me35_i_codigo == null) || ($this->me35_i_codigo == "") ){ 
       $this->erro_sql = " Campo me35_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_alimento(
                                       me35_i_codigo 
                                      ,me35_c_nomealimento 
                                      ,me35_c_nomecientifico 
                                      ,me35_i_grupoalimentar 
                                      ,me35_c_fonteinformacao 
                                      ,me35_i_unidade 
                                      ,me35_c_quant 
                       )
                values (
                                $this->me35_i_codigo 
                               ,'$this->me35_c_nomealimento' 
                               ,'$this->me35_c_nomecientifico' 
                               ,$this->me35_i_grupoalimentar 
                               ,'$this->me35_c_fonteinformacao' 
                               ,$this->me35_i_unidade 
                               ,'$this->me35_c_quant' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_alimento ($this->me35_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_alimento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_alimento ($this->me35_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me35_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me35_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17375,'$this->me35_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3075,17375,'','".AddSlashes(pg_result($resaco,0,'me35_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3075,17376,'','".AddSlashes(pg_result($resaco,0,'me35_c_nomealimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3075,17377,'','".AddSlashes(pg_result($resaco,0,'me35_c_nomecientifico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3075,17378,'','".AddSlashes(pg_result($resaco,0,'me35_i_grupoalimentar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3075,17379,'','".AddSlashes(pg_result($resaco,0,'me35_c_fonteinformacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3075,17407,'','".AddSlashes(pg_result($resaco,0,'me35_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3075,17406,'','".AddSlashes(pg_result($resaco,0,'me35_c_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me35_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_alimento set ";
     $virgula = "";
     if(trim($this->me35_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_i_codigo"])){ 
       $sql  .= $virgula." me35_i_codigo = $this->me35_i_codigo ";
       $virgula = ",";
       if(trim($this->me35_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "me35_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me35_c_nomealimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_c_nomealimento"])){ 
       $sql  .= $virgula." me35_c_nomealimento = '$this->me35_c_nomealimento' ";
       $virgula = ",";
       if(trim($this->me35_c_nomealimento) == null ){ 
         $this->erro_sql = " Campo Alimento nao Informado.";
         $this->erro_campo = "me35_c_nomealimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me35_c_nomecientifico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_c_nomecientifico"])){ 
       $sql  .= $virgula." me35_c_nomecientifico = '$this->me35_c_nomecientifico' ";
       $virgula = ",";
       if(trim($this->me35_c_nomecientifico) == null ){ 
         $this->erro_sql = " Campo Nome Cient�fico nao Informado.";
         $this->erro_campo = "me35_c_nomecientifico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me35_i_grupoalimentar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_i_grupoalimentar"])){ 
       $sql  .= $virgula." me35_i_grupoalimentar = $this->me35_i_grupoalimentar ";
       $virgula = ",";
       if(trim($this->me35_i_grupoalimentar) == null ){ 
         $this->erro_sql = " Campo Grupo alimentar nao Informado.";
         $this->erro_campo = "me35_i_grupoalimentar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me35_c_fonteinformacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_c_fonteinformacao"])){ 
       $sql  .= $virgula." me35_c_fonteinformacao = '$this->me35_c_fonteinformacao' ";
       $virgula = ",";
       if(trim($this->me35_c_fonteinformacao) == null ){ 
         $this->erro_sql = " Campo Fonte da Informa��o nao Informado.";
         $this->erro_campo = "me35_c_fonteinformacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me35_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_i_unidade"])){ 
       $sql  .= $virgula." me35_i_unidade = $this->me35_i_unidade ";
       $virgula = ",";
       if(trim($this->me35_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "me35_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me35_c_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me35_c_quant"])){ 
       $sql  .= $virgula." me35_c_quant = '$this->me35_c_quant' ";
       $virgula = ",";
       if(trim($this->me35_c_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "me35_c_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me35_i_codigo!=null){
       $sql .= " me35_i_codigo = $this->me35_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me35_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17375,'$this->me35_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_i_codigo"]) || $this->me35_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3075,17375,'".AddSlashes(pg_result($resaco,$conresaco,'me35_i_codigo'))."','$this->me35_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_c_nomealimento"]) || $this->me35_c_nomealimento != "")
           $resac = db_query("insert into db_acount values($acount,3075,17376,'".AddSlashes(pg_result($resaco,$conresaco,'me35_c_nomealimento'))."','$this->me35_c_nomealimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_c_nomecientifico"]) || $this->me35_c_nomecientifico != "")
           $resac = db_query("insert into db_acount values($acount,3075,17377,'".AddSlashes(pg_result($resaco,$conresaco,'me35_c_nomecientifico'))."','$this->me35_c_nomecientifico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_i_grupoalimentar"]) || $this->me35_i_grupoalimentar != "")
           $resac = db_query("insert into db_acount values($acount,3075,17378,'".AddSlashes(pg_result($resaco,$conresaco,'me35_i_grupoalimentar'))."','$this->me35_i_grupoalimentar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_c_fonteinformacao"]) || $this->me35_c_fonteinformacao != "")
           $resac = db_query("insert into db_acount values($acount,3075,17379,'".AddSlashes(pg_result($resaco,$conresaco,'me35_c_fonteinformacao'))."','$this->me35_c_fonteinformacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_i_unidade"]) || $this->me35_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,3075,17407,'".AddSlashes(pg_result($resaco,$conresaco,'me35_i_unidade'))."','$this->me35_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me35_c_quant"]) || $this->me35_c_quant != "")
           $resac = db_query("insert into db_acount values($acount,3075,17406,'".AddSlashes(pg_result($resaco,$conresaco,'me35_c_quant'))."','$this->me35_c_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_alimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me35_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_alimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me35_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me35_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17375,'$me35_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3075,17375,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3075,17376,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_c_nomealimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3075,17377,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_c_nomecientifico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3075,17378,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_i_grupoalimentar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3075,17379,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_c_fonteinformacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3075,17407,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3075,17406,'','".AddSlashes(pg_result($resaco,$iresaco,'me35_c_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_alimento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me35_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me35_i_codigo = $me35_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_alimento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me35_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_alimento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:mer_alimento";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_alimento ";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = mer_alimento.me35_i_unidade";
     $sql .= "      inner join mer_grupoalimento  on  mer_grupoalimento.me30_i_codigo = mer_alimento.me35_i_grupoalimentar";
     $sql2 = "";
     if($dbwhere==""){
       if($me35_i_codigo!=null ){
         $sql2 .= " where mer_alimento.me35_i_codigo = $me35_i_codigo "; 
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
   function sql_query_file ( $me35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_alimento ";
     $sql2 = "";
     if($dbwhere==""){
       if($me35_i_codigo!=null ){
         $sql2 .= " where mer_alimento.me35_i_codigo = $me35_i_codigo "; 
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