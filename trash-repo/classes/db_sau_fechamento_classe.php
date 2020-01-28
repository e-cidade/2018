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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_fechamento
class cl_sau_fechamento { 
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
   var $sd97_i_codigo = 0; 
   var $sd97_i_login = 0; 
   var $sd97_d_data_dia = null; 
   var $sd97_d_data_mes = null; 
   var $sd97_d_data_ano = null; 
   var $sd97_d_data = null; 
   var $sd97_c_hora = null; 
   var $sd97_d_dataini_dia = null; 
   var $sd97_d_dataini_mes = null; 
   var $sd97_d_dataini_ano = null; 
   var $sd97_d_dataini = null; 
   var $sd97_d_datafim_dia = null; 
   var $sd97_d_datafim_mes = null; 
   var $sd97_d_datafim_ano = null; 
   var $sd97_d_datafim = null; 
   var $sd97_c_descricao = null; 
   var $sd97_c_tipo = null; 
   var $sd97_i_compmes = 0; 
   var $sd97_i_compano = 0; 
   var $sd97_i_financiamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd97_i_codigo = int4 = Código 
                 sd97_i_login = int4 = Login 
                 sd97_d_data = date = Data 
                 sd97_c_hora = char(20) = Hora 
                 sd97_d_dataini = date = Data Inicial 
                 sd97_d_datafim = date = Data Final 
                 sd97_c_descricao = char(50) = Descrição 
                 sd97_c_tipo = char(7) = Tipo 
                 sd97_i_compmes = int4 = Mês Competência 
                 sd97_i_compano = int4 = Ano Competência 
                 sd97_i_financiamento = int4 = Financiamento 
                 ";
   //funcao construtor da classe 
   function cl_sau_fechamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_fechamento"); 
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
       $this->sd97_i_codigo = ($this->sd97_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_i_codigo"]:$this->sd97_i_codigo);
       $this->sd97_i_login = ($this->sd97_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_i_login"]:$this->sd97_i_login);
       if($this->sd97_d_data == ""){
         $this->sd97_d_data_dia = ($this->sd97_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_data_dia"]:$this->sd97_d_data_dia);
         $this->sd97_d_data_mes = ($this->sd97_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_data_mes"]:$this->sd97_d_data_mes);
         $this->sd97_d_data_ano = ($this->sd97_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_data_ano"]:$this->sd97_d_data_ano);
         if($this->sd97_d_data_dia != ""){
            $this->sd97_d_data = $this->sd97_d_data_ano."-".$this->sd97_d_data_mes."-".$this->sd97_d_data_dia;
         }
       }
       $this->sd97_c_hora = ($this->sd97_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_c_hora"]:$this->sd97_c_hora);
       if($this->sd97_d_dataini == ""){
         $this->sd97_d_dataini_dia = ($this->sd97_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini_dia"]:$this->sd97_d_dataini_dia);
         $this->sd97_d_dataini_mes = ($this->sd97_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini_mes"]:$this->sd97_d_dataini_mes);
         $this->sd97_d_dataini_ano = ($this->sd97_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini_ano"]:$this->sd97_d_dataini_ano);
         if($this->sd97_d_dataini_dia != ""){
            $this->sd97_d_dataini = $this->sd97_d_dataini_ano."-".$this->sd97_d_dataini_mes."-".$this->sd97_d_dataini_dia;
         }
       }
       if($this->sd97_d_datafim == ""){
         $this->sd97_d_datafim_dia = ($this->sd97_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim_dia"]:$this->sd97_d_datafim_dia);
         $this->sd97_d_datafim_mes = ($this->sd97_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim_mes"]:$this->sd97_d_datafim_mes);
         $this->sd97_d_datafim_ano = ($this->sd97_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim_ano"]:$this->sd97_d_datafim_ano);
         if($this->sd97_d_datafim_dia != ""){
            $this->sd97_d_datafim = $this->sd97_d_datafim_ano."-".$this->sd97_d_datafim_mes."-".$this->sd97_d_datafim_dia;
         }
       }
       $this->sd97_c_descricao = ($this->sd97_c_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_c_descricao"]:$this->sd97_c_descricao);
       $this->sd97_c_tipo = ($this->sd97_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_c_tipo"]:$this->sd97_c_tipo);
       $this->sd97_i_compmes = ($this->sd97_i_compmes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_i_compmes"]:$this->sd97_i_compmes);
       $this->sd97_i_compano = ($this->sd97_i_compano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_i_compano"]:$this->sd97_i_compano);
       $this->sd97_i_financiamento = ($this->sd97_i_financiamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_i_financiamento"]:$this->sd97_i_financiamento);
     }else{
       $this->sd97_i_codigo = ($this->sd97_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd97_i_codigo"]:$this->sd97_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd97_i_codigo){ 
      $this->atualizacampos();
     if($this->sd97_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "sd97_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd97_d_data == null ){ 
       $this->sd97_d_data = "now()";
     }
     if($this->sd97_c_hora == null ){ 
       $this->sd97_c_hora = "'||current_time||'";
     }
     if($this->sd97_d_dataini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "sd97_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd97_d_datafim == null ){ 
       $this->sd97_d_datafim = "null";
     }
     if($this->sd97_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "sd97_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd97_i_compmes == null ){ 
       $this->erro_sql = " Campo Mês Competência nao Informado.";
       $this->erro_campo = "sd97_i_compmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd97_i_compano == null ){ 
       $this->erro_sql = " Campo Ano Competência nao Informado.";
       $this->erro_campo = "sd97_i_compano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd97_i_financiamento == null ){ 
       $this->sd97_i_financiamento = "0";
     }
     if($sd97_i_codigo == "" || $sd97_i_codigo == null ){
       $result = db_query("select nextval('sau_fechamento_sd97_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_fechamento_sd97_codigo_seq do campo: sd97_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd97_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_fechamento_sd97_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd97_i_codigo)){
         $this->erro_sql = " Campo sd97_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd97_i_codigo = $sd97_i_codigo; 
       }
     }
     if(($this->sd97_i_codigo == null) || ($this->sd97_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd97_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_fechamento(
                                       sd97_i_codigo 
                                      ,sd97_i_login 
                                      ,sd97_d_data 
                                      ,sd97_c_hora 
                                      ,sd97_d_dataini 
                                      ,sd97_d_datafim 
                                      ,sd97_c_descricao 
                                      ,sd97_c_tipo 
                                      ,sd97_i_compmes 
                                      ,sd97_i_compano 
                                      ,sd97_i_financiamento 
                       )
                values (
                                $this->sd97_i_codigo 
                               ,$this->sd97_i_login 
                               ,".($this->sd97_d_data == "null" || $this->sd97_d_data == ""?"null":"'".$this->sd97_d_data."'")." 
                               ,'$this->sd97_c_hora' 
                               ,".($this->sd97_d_dataini == "null" || $this->sd97_d_dataini == ""?"null":"'".$this->sd97_d_dataini."'")." 
                               ,".($this->sd97_d_datafim == "null" || $this->sd97_d_datafim == ""?"null":"'".$this->sd97_d_datafim."'")." 
                               ,'$this->sd97_c_descricao' 
                               ,'$this->sd97_c_tipo' 
                               ,$this->sd97_i_compmes 
                               ,$this->sd97_i_compano 
                               ,$this->sd97_i_financiamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_fechamento ($this->sd97_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_fechamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_fechamento ($this->sd97_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd97_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd97_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12335,'$this->sd97_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2153,12335,'','".AddSlashes(pg_result($resaco,0,'sd97_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12336,'','".AddSlashes(pg_result($resaco,0,'sd97_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12337,'','".AddSlashes(pg_result($resaco,0,'sd97_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12338,'','".AddSlashes(pg_result($resaco,0,'sd97_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12340,'','".AddSlashes(pg_result($resaco,0,'sd97_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12341,'','".AddSlashes(pg_result($resaco,0,'sd97_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12342,'','".AddSlashes(pg_result($resaco,0,'sd97_c_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12343,'','".AddSlashes(pg_result($resaco,0,'sd97_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12344,'','".AddSlashes(pg_result($resaco,0,'sd97_i_compmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,12346,'','".AddSlashes(pg_result($resaco,0,'sd97_i_compano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2153,17760,'','".AddSlashes(pg_result($resaco,0,'sd97_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd97_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_fechamento set ";
     $virgula = "";
     if(trim($this->sd97_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_codigo"])){ 
       $sql  .= $virgula." sd97_i_codigo = $this->sd97_i_codigo ";
       $virgula = ",";
       if(trim($this->sd97_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd97_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd97_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_login"])){ 
       $sql  .= $virgula." sd97_i_login = $this->sd97_i_login ";
       $virgula = ",";
       if(trim($this->sd97_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "sd97_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd97_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd97_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd97_d_data = '$this->sd97_d_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_data_dia"])){ 
         $sql  .= $virgula." sd97_d_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd97_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_c_hora"])){ 
       $sql  .= $virgula." sd97_c_hora = '$this->sd97_c_hora' ";
       $virgula = ",";
     }
     if(trim($this->sd97_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini_dia"] !="") ){ 
       $sql  .= $virgula." sd97_d_dataini = '$this->sd97_d_dataini' ";
       $virgula = ",";
       if(trim($this->sd97_d_dataini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "sd97_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini_dia"])){ 
         $sql  .= $virgula." sd97_d_dataini = null ";
         $virgula = ",";
         if(trim($this->sd97_d_dataini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "sd97_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd97_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." sd97_d_datafim = '$this->sd97_d_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim_dia"])){ 
         $sql  .= $virgula." sd97_d_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd97_c_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_c_descricao"])){ 
       $sql  .= $virgula." sd97_c_descricao = '$this->sd97_c_descricao' ";
       $virgula = ",";
     }
     if(trim($this->sd97_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_c_tipo"])){ 
       $sql  .= $virgula." sd97_c_tipo = '$this->sd97_c_tipo' ";
       $virgula = ",";
       if(trim($this->sd97_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "sd97_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd97_i_compmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_compmes"])){ 
       $sql  .= $virgula." sd97_i_compmes = $this->sd97_i_compmes ";
       $virgula = ",";
       if(trim($this->sd97_i_compmes) == null ){ 
         $this->erro_sql = " Campo Mês Competência nao Informado.";
         $this->erro_campo = "sd97_i_compmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd97_i_compano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_compano"])){ 
       $sql  .= $virgula." sd97_i_compano = $this->sd97_i_compano ";
       $virgula = ",";
       if(trim($this->sd97_i_compano) == null ){ 
         $this->erro_sql = " Campo Ano Competência nao Informado.";
         $this->erro_campo = "sd97_i_compano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd97_i_financiamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_financiamento"])){ 
        if(trim($this->sd97_i_financiamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_financiamento"])){ 
           $this->sd97_i_financiamento = "0" ; 
        } 
       $sql  .= $virgula." sd97_i_financiamento = $this->sd97_i_financiamento ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd97_i_codigo!=null){
       $sql .= " sd97_i_codigo = $this->sd97_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd97_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12335,'$this->sd97_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_codigo"]) || $this->sd97_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2153,12335,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_i_codigo'))."','$this->sd97_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_login"]) || $this->sd97_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2153,12336,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_i_login'))."','$this->sd97_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_data"]) || $this->sd97_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2153,12337,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_d_data'))."','$this->sd97_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_c_hora"]) || $this->sd97_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2153,12338,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_c_hora'))."','$this->sd97_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_dataini"]) || $this->sd97_d_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2153,12340,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_d_dataini'))."','$this->sd97_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_d_datafim"]) || $this->sd97_d_datafim != "")
           $resac = db_query("insert into db_acount values($acount,2153,12341,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_d_datafim'))."','$this->sd97_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_c_descricao"]) || $this->sd97_c_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2153,12342,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_c_descricao'))."','$this->sd97_c_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_c_tipo"]) || $this->sd97_c_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2153,12343,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_c_tipo'))."','$this->sd97_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_compmes"]) || $this->sd97_i_compmes != "")
           $resac = db_query("insert into db_acount values($acount,2153,12344,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_i_compmes'))."','$this->sd97_i_compmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_compano"]) || $this->sd97_i_compano != "")
           $resac = db_query("insert into db_acount values($acount,2153,12346,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_i_compano'))."','$this->sd97_i_compano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd97_i_financiamento"]) || $this->sd97_i_financiamento != "")
           $resac = db_query("insert into db_acount values($acount,2153,17760,'".AddSlashes(pg_result($resaco,$conresaco,'sd97_i_financiamento'))."','$this->sd97_i_financiamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_fechamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd97_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_fechamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd97_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd97_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd97_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd97_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12335,'$sd97_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2153,12335,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12336,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12337,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12338,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12340,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12341,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12342,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_c_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12343,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12344,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_i_compmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,12346,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_i_compano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2153,17760,'','".AddSlashes(pg_result($resaco,$iresaco,'sd97_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_fechamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd97_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd97_i_codigo = $sd97_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_fechamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd97_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_fechamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd97_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd97_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_fechamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd97_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_fechamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_fechamento.sd97_i_login";
     $sql .= "      left  join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_fechamento.sd97_i_financiamento";
     $sql2 = "";
     if($dbwhere==""){
       if($sd97_i_codigo!=null ){
         $sql2 .= " where sau_fechamento.sd97_i_codigo = $sd97_i_codigo "; 
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
   function sql_query_file ( $sd97_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_fechamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd97_i_codigo!=null ){
         $sql2 .= " where sau_fechamento.sd97_i_codigo = $sd97_i_codigo "; 
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