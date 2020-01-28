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
//CLASSE DA ENTIDADE mer_tipocardapio
class cl_mer_tipocardapio { 
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
   var $me27_i_codigo = 0; 
   var $me27_c_nome = null; 
   var $me27_c_ativo = null; 
   var $me27_d_inicio_dia = null; 
   var $me27_d_inicio_mes = null; 
   var $me27_d_inicio_ano = null; 
   var $me27_d_inicio = null; 
   var $me27_d_fim_dia = null; 
   var $me27_d_fim_mes = null; 
   var $me27_d_fim_ano = null; 
   var $me27_d_fim = null; 
   var $me27_f_versao = 0; 
   var $me27_i_id = 0; 
   var $me27_i_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me27_i_codigo = int4 = Código 
                 me27_c_nome = char(100) = Nome 
                 me27_c_ativo = char(1) = Ativo 
                 me27_d_inicio = date = Data de Validade 
                 me27_d_fim = date = Data de Validade 
                 me27_f_versao = float4 = Versão 
                 me27_i_id = int4 = ID 
                 me27_i_ano = int4 = Ano do Cardápio 
                 ";
   //funcao construtor da classe 
   function cl_mer_tipocardapio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_tipocardapio"); 
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
       $this->me27_i_codigo = ($this->me27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_i_codigo"]:$this->me27_i_codigo);
       $this->me27_c_nome = ($this->me27_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_c_nome"]:$this->me27_c_nome);
       $this->me27_c_ativo = ($this->me27_c_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_c_ativo"]:$this->me27_c_ativo);
       if($this->me27_d_inicio == ""){
         $this->me27_d_inicio_dia = ($this->me27_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_d_inicio_dia"]:$this->me27_d_inicio_dia);
         $this->me27_d_inicio_mes = ($this->me27_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_d_inicio_mes"]:$this->me27_d_inicio_mes);
         $this->me27_d_inicio_ano = ($this->me27_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_d_inicio_ano"]:$this->me27_d_inicio_ano);
         if($this->me27_d_inicio_dia != ""){
            $this->me27_d_inicio = $this->me27_d_inicio_ano."-".$this->me27_d_inicio_mes."-".$this->me27_d_inicio_dia;
         }
       }
       if($this->me27_d_fim == ""){
         $this->me27_d_fim_dia = ($this->me27_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_d_fim_dia"]:$this->me27_d_fim_dia);
         $this->me27_d_fim_mes = ($this->me27_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_d_fim_mes"]:$this->me27_d_fim_mes);
         $this->me27_d_fim_ano = ($this->me27_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_d_fim_ano"]:$this->me27_d_fim_ano);
         if($this->me27_d_fim_dia != ""){
            $this->me27_d_fim = $this->me27_d_fim_ano."-".$this->me27_d_fim_mes."-".$this->me27_d_fim_dia;
         }
       }
       $this->me27_f_versao = ($this->me27_f_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_f_versao"]:$this->me27_f_versao);
       $this->me27_i_id = ($this->me27_i_id == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_i_id"]:$this->me27_i_id);
       $this->me27_i_ano = ($this->me27_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_i_ano"]:$this->me27_i_ano);
     }else{
       $this->me27_i_codigo = ($this->me27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me27_i_codigo"]:$this->me27_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me27_i_codigo){ 
      $this->atualizacampos();
     if($this->me27_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "me27_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me27_c_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "me27_c_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me27_d_inicio == null ){ 
       $this->erro_sql = " Campo Data de Validade nao Informado.";
       $this->erro_campo = "me27_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me27_d_fim == null ){ 
       $this->me27_d_fim = "null";
     }
     if($this->me27_f_versao == null ){ 
       $this->erro_sql = " Campo Versão nao Informado.";
       $this->erro_campo = "me27_f_versao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me27_i_id == null ){ 
       $this->erro_sql = " Campo ID nao Informado.";
       $this->erro_campo = "me27_i_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me27_i_ano == null ){ 
       $this->erro_sql = " Campo Ano do Cardápio nao Informado.";
       $this->erro_campo = "me27_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me27_i_codigo == "" || $me27_i_codigo == null ){
       $result = db_query("select nextval('mer_tipocardapio_me27_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_tipocardapio_me27_codigo_seq do campo: me27_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me27_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_tipocardapio_me27_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me27_i_codigo)){
         $this->erro_sql = " Campo me27_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me27_i_codigo = $me27_i_codigo; 
       }
     }
     if(($this->me27_i_codigo == null) || ($this->me27_i_codigo == "") ){ 
       $this->erro_sql = " Campo me27_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_tipocardapio(
                                       me27_i_codigo 
                                      ,me27_c_nome 
                                      ,me27_c_ativo 
                                      ,me27_d_inicio 
                                      ,me27_d_fim 
                                      ,me27_f_versao 
                                      ,me27_i_id 
                                      ,me27_i_ano 
                       )
                values (
                                $this->me27_i_codigo 
                               ,'$this->me27_c_nome' 
                               ,'$this->me27_c_ativo' 
                               ,".($this->me27_d_inicio == "null" || $this->me27_d_inicio == ""?"null":"'".$this->me27_d_inicio."'")." 
                               ,".($this->me27_d_fim == "null" || $this->me27_d_fim == ""?"null":"'".$this->me27_d_fim."'")." 
                               ,$this->me27_f_versao 
                               ,$this->me27_i_id 
                               ,$this->me27_i_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_tipocardapio ($this->me27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_tipocardapio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_tipocardapio ($this->me27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me27_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me27_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14159,'$this->me27_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2489,14159,'','".AddSlashes(pg_result($resaco,0,'me27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,14160,'','".AddSlashes(pg_result($resaco,0,'me27_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,14161,'','".AddSlashes(pg_result($resaco,0,'me27_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,15215,'','".AddSlashes(pg_result($resaco,0,'me27_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,15216,'','".AddSlashes(pg_result($resaco,0,'me27_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,17383,'','".AddSlashes(pg_result($resaco,0,'me27_f_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,17384,'','".AddSlashes(pg_result($resaco,0,'me27_i_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2489,14165,'','".AddSlashes(pg_result($resaco,0,'me27_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me27_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_tipocardapio set ";
     $virgula = "";
     if(trim($this->me27_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_i_codigo"])){ 
       $sql  .= $virgula." me27_i_codigo = $this->me27_i_codigo ";
       $virgula = ",";
       if(trim($this->me27_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me27_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me27_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_c_nome"])){ 
       $sql  .= $virgula." me27_c_nome = '$this->me27_c_nome' ";
       $virgula = ",";
       if(trim($this->me27_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "me27_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me27_c_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_c_ativo"])){ 
       $sql  .= $virgula." me27_c_ativo = '$this->me27_c_ativo' ";
       $virgula = ",";
       if(trim($this->me27_c_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "me27_c_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me27_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me27_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." me27_d_inicio = '$this->me27_d_inicio' ";
       $virgula = ",";
       if(trim($this->me27_d_inicio) == null ){ 
         $this->erro_sql = " Campo Data de Validade nao Informado.";
         $this->erro_campo = "me27_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me27_d_inicio_dia"])){ 
         $sql  .= $virgula." me27_d_inicio = null ";
         $virgula = ",";
         if(trim($this->me27_d_inicio) == null ){ 
           $this->erro_sql = " Campo Data de Validade nao Informado.";
           $this->erro_campo = "me27_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->me27_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me27_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." me27_d_fim = '$this->me27_d_fim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me27_d_fim_dia"])){ 
         $sql  .= $virgula." me27_d_fim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->me27_f_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_f_versao"])){ 
       $sql  .= $virgula." me27_f_versao = $this->me27_f_versao ";
       $virgula = ",";
       if(trim($this->me27_f_versao) == null ){ 
         $this->erro_sql = " Campo Versão nao Informado.";
         $this->erro_campo = "me27_f_versao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me27_i_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_i_id"])){ 
       $sql  .= $virgula." me27_i_id = $this->me27_i_id ";
       $virgula = ",";
       if(trim($this->me27_i_id) == null ){ 
         $this->erro_sql = " Campo ID nao Informado.";
         $this->erro_campo = "me27_i_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me27_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me27_i_ano"])){ 
       $sql  .= $virgula." me27_i_ano = $this->me27_i_ano ";
       $virgula = ",";
       if(trim($this->me27_i_ano) == null ){ 
         $this->erro_sql = " Campo Ano do Cardápio nao Informado.";
         $this->erro_campo = "me27_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me27_i_codigo!=null){
       $sql .= " me27_i_codigo = $this->me27_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me27_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14159,'$this->me27_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_i_codigo"]) || $this->me27_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2489,14159,'".AddSlashes(pg_result($resaco,$conresaco,'me27_i_codigo'))."','$this->me27_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_c_nome"]) || $this->me27_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,2489,14160,'".AddSlashes(pg_result($resaco,$conresaco,'me27_c_nome'))."','$this->me27_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_c_ativo"]) || $this->me27_c_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2489,14161,'".AddSlashes(pg_result($resaco,$conresaco,'me27_c_ativo'))."','$this->me27_c_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_d_inicio"]) || $this->me27_d_inicio != "")
           $resac = db_query("insert into db_acount values($acount,2489,15215,'".AddSlashes(pg_result($resaco,$conresaco,'me27_d_inicio'))."','$this->me27_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_d_fim"]) || $this->me27_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2489,15216,'".AddSlashes(pg_result($resaco,$conresaco,'me27_d_fim'))."','$this->me27_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_f_versao"]) || $this->me27_f_versao != "")
           $resac = db_query("insert into db_acount values($acount,2489,17383,'".AddSlashes(pg_result($resaco,$conresaco,'me27_f_versao'))."','$this->me27_f_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_i_id"]) || $this->me27_i_id != "")
           $resac = db_query("insert into db_acount values($acount,2489,17384,'".AddSlashes(pg_result($resaco,$conresaco,'me27_i_id'))."','$this->me27_i_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me27_i_ano"]) || $this->me27_i_ano != "")
           $resac = db_query("insert into db_acount values($acount,2489,14165,'".AddSlashes(pg_result($resaco,$conresaco,'me27_i_ano'))."','$this->me27_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_tipocardapio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_tipocardapio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me27_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me27_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14159,'$me27_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2489,14159,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,14160,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,14161,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,15215,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,15216,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,17383,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_f_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,17384,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_i_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2489,14165,'','".AddSlashes(pg_result($resaco,$iresaco,'me27_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_tipocardapio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me27_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me27_i_codigo = $me27_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_tipocardapio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_tipocardapio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me27_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_tipocardapio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_tipocardapio ";
     $sql2 = "";
     if($dbwhere==""){
       if($me27_i_codigo!=null ){
         $sql2 .= " where mer_tipocardapio.me27_i_codigo = $me27_i_codigo "; 
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
   function sql_query_file ( $me27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_tipocardapio ";
     $sql2 = "";
     if($dbwhere==""){
       if($me27_i_codigo!=null ){
         $sql2 .= " where mer_tipocardapio.me27_i_codigo = $me27_i_codigo "; 
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