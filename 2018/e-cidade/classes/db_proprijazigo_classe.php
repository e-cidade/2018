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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE proprijazigo
class cl_proprijazigo { 
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
   var $cm29_i_codigo = 0; 
   var $cm29_i_propricemit = 0; 
   var $cm29_i_termo = 0; 
   var $cm29_d_termo_dia = null; 
   var $cm29_d_termo_mes = null; 
   var $cm29_d_termo_ano = null; 
   var $cm29_d_termo = null; 
   var $cm29_t_termo = null; 
   var $cm29_i_concessao = 0; 
   var $cm29_d_concessao_dia = null; 
   var $cm29_d_concessao_mes = null; 
   var $cm29_d_concessao_ano = null; 
   var $cm29_d_concessao = null; 
   var $cm29_t_concessao = null; 
   var $cm29_d_estrutura_dia = null; 
   var $cm29_d_estrutura_mes = null; 
   var $cm29_d_estrutura_ano = null; 
   var $cm29_d_estrutura = null; 
   var $cm29_d_base_dia = null; 
   var $cm29_d_base_mes = null; 
   var $cm29_d_base_ano = null; 
   var $cm29_d_base = null; 
   var $cm29_d_pronto_dia = null; 
   var $cm29_d_pronto_mes = null; 
   var $cm29_d_pronto_ano = null; 
   var $cm29_d_pronto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm29_i_codigo = int4 = Código 
                 cm29_i_propricemit = int4 = Código Propricemit 
                 cm29_i_termo = int4 = Numero Termo 
                 cm29_d_termo = date = Data Termo 
                 cm29_t_termo = text = Termo 
                 cm29_i_concessao = int4 = Numero da Concessão 
                 cm29_d_concessao = date = Data Concessão 
                 cm29_t_concessao = text = Concessão 
                 cm29_d_estrutura = date = Estrutura 
                 cm29_d_base = date = Base 
                 cm29_d_pronto = date = Pronto 
                 ";
   //funcao construtor da classe 
   function cl_proprijazigo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("proprijazigo"); 
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
       $this->cm29_i_codigo = ($this->cm29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_i_codigo"]:$this->cm29_i_codigo);
       $this->cm29_i_propricemit = ($this->cm29_i_propricemit == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_i_propricemit"]:$this->cm29_i_propricemit);
       $this->cm29_i_termo = ($this->cm29_i_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_i_termo"]:$this->cm29_i_termo);
       if($this->cm29_d_termo == ""){
         $this->cm29_d_termo_dia = ($this->cm29_d_termo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_termo_dia"]:$this->cm29_d_termo_dia);
         $this->cm29_d_termo_mes = ($this->cm29_d_termo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_termo_mes"]:$this->cm29_d_termo_mes);
         $this->cm29_d_termo_ano = ($this->cm29_d_termo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_termo_ano"]:$this->cm29_d_termo_ano);
         if($this->cm29_d_termo_dia != ""){
            $this->cm29_d_termo = $this->cm29_d_termo_ano."-".$this->cm29_d_termo_mes."-".$this->cm29_d_termo_dia;
         }
       }
       $this->cm29_t_termo = ($this->cm29_t_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_t_termo"]:$this->cm29_t_termo);
       $this->cm29_i_concessao = ($this->cm29_i_concessao == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_i_concessao"]:$this->cm29_i_concessao);
       if($this->cm29_d_concessao == ""){
         $this->cm29_d_concessao_dia = ($this->cm29_d_concessao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao_dia"]:$this->cm29_d_concessao_dia);
         $this->cm29_d_concessao_mes = ($this->cm29_d_concessao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao_mes"]:$this->cm29_d_concessao_mes);
         $this->cm29_d_concessao_ano = ($this->cm29_d_concessao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao_ano"]:$this->cm29_d_concessao_ano);
         if($this->cm29_d_concessao_dia != ""){
            $this->cm29_d_concessao = $this->cm29_d_concessao_ano."-".$this->cm29_d_concessao_mes."-".$this->cm29_d_concessao_dia;
         }
       }
       $this->cm29_t_concessao = ($this->cm29_t_concessao == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_t_concessao"]:$this->cm29_t_concessao);
       if($this->cm29_d_estrutura == ""){
         $this->cm29_d_estrutura_dia = ($this->cm29_d_estrutura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura_dia"]:$this->cm29_d_estrutura_dia);
         $this->cm29_d_estrutura_mes = ($this->cm29_d_estrutura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura_mes"]:$this->cm29_d_estrutura_mes);
         $this->cm29_d_estrutura_ano = ($this->cm29_d_estrutura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura_ano"]:$this->cm29_d_estrutura_ano);
         if($this->cm29_d_estrutura_dia != ""){
            $this->cm29_d_estrutura = $this->cm29_d_estrutura_ano."-".$this->cm29_d_estrutura_mes."-".$this->cm29_d_estrutura_dia;
         }
       }
       if($this->cm29_d_base == ""){
         $this->cm29_d_base_dia = ($this->cm29_d_base_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_base_dia"]:$this->cm29_d_base_dia);
         $this->cm29_d_base_mes = ($this->cm29_d_base_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_base_mes"]:$this->cm29_d_base_mes);
         $this->cm29_d_base_ano = ($this->cm29_d_base_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_base_ano"]:$this->cm29_d_base_ano);
         if($this->cm29_d_base_dia != ""){
            $this->cm29_d_base = $this->cm29_d_base_ano."-".$this->cm29_d_base_mes."-".$this->cm29_d_base_dia;
         }
       }
       if($this->cm29_d_pronto == ""){
         $this->cm29_d_pronto_dia = ($this->cm29_d_pronto_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto_dia"]:$this->cm29_d_pronto_dia);
         $this->cm29_d_pronto_mes = ($this->cm29_d_pronto_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto_mes"]:$this->cm29_d_pronto_mes);
         $this->cm29_d_pronto_ano = ($this->cm29_d_pronto_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto_ano"]:$this->cm29_d_pronto_ano);
         if($this->cm29_d_pronto_dia != ""){
            $this->cm29_d_pronto = $this->cm29_d_pronto_ano."-".$this->cm29_d_pronto_mes."-".$this->cm29_d_pronto_dia;
         }
       }
     }else{
       $this->cm29_i_codigo = ($this->cm29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm29_i_codigo"]:$this->cm29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm29_i_codigo){ 
      $this->atualizacampos();
     if($this->cm29_i_propricemit == null ){ 
       $this->erro_sql = " Campo Código Propricemit nao Informado.";
       $this->erro_campo = "cm29_i_propricemit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm29_i_termo == null ){ 
       $this->erro_sql = " Campo Numero Termo nao Informado.";
       $this->erro_campo = "cm29_i_termo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm29_d_termo == null ){ 
       $this->cm29_d_termo = "null";
     }
     if($this->cm29_i_concessao == null ){ 
       $this->erro_sql = " Campo Numero da Concessão nao Informado.";
       $this->erro_campo = "cm29_i_concessao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm29_d_concessao == null ){ 
       $this->cm29_d_concessao = "null";
     }
     if($this->cm29_d_estrutura == null ){ 
       $this->cm29_d_estrutura = "null";
     }
     if($this->cm29_d_base == null ){ 
       $this->cm29_d_base = "null";
     }
     if($this->cm29_d_pronto == null ){ 
       $this->cm29_d_pronto = "null";
     }
     if($cm29_i_codigo == "" || $cm29_i_codigo == null ){
       $result = db_query("select nextval('proprijazigo_cm29_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: proprijazigo_cm29_i_codigo_seq do campo: cm29_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm29_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from proprijazigo_cm29_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm29_i_codigo)){
         $this->erro_sql = " Campo cm29_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm29_i_codigo = $cm29_i_codigo; 
       }
     }
     if(($this->cm29_i_codigo == null) || ($this->cm29_i_codigo == "") ){ 
       $this->erro_sql = " Campo cm29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into proprijazigo(
                                       cm29_i_codigo 
                                      ,cm29_i_propricemit 
                                      ,cm29_i_termo 
                                      ,cm29_d_termo 
                                      ,cm29_t_termo 
                                      ,cm29_i_concessao 
                                      ,cm29_d_concessao 
                                      ,cm29_t_concessao 
                                      ,cm29_d_estrutura 
                                      ,cm29_d_base 
                                      ,cm29_d_pronto 
                       )
                values (
                                $this->cm29_i_codigo 
                               ,$this->cm29_i_propricemit 
                               ,$this->cm29_i_termo 
                               ,".($this->cm29_d_termo == "null" || $this->cm29_d_termo == ""?"null":"'".$this->cm29_d_termo."'")." 
                               ,'$this->cm29_t_termo' 
                               ,$this->cm29_i_concessao 
                               ,".($this->cm29_d_concessao == "null" || $this->cm29_d_concessao == ""?"null":"'".$this->cm29_d_concessao."'")." 
                               ,'$this->cm29_t_concessao' 
                               ,".($this->cm29_d_estrutura == "null" || $this->cm29_d_estrutura == ""?"null":"'".$this->cm29_d_estrutura."'")." 
                               ,".($this->cm29_d_base == "null" || $this->cm29_d_base == ""?"null":"'".$this->cm29_d_base."'")." 
                               ,".($this->cm29_d_pronto == "null" || $this->cm29_d_pronto == ""?"null":"'".$this->cm29_d_pronto."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Proprietário do Jazigo ($this->cm29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Proprietário do Jazigo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Proprietário do Jazigo ($this->cm29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm29_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10373,'$this->cm29_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1795,10373,'','".AddSlashes(pg_result($resaco,0,'cm29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10374,'','".AddSlashes(pg_result($resaco,0,'cm29_i_propricemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10375,'','".AddSlashes(pg_result($resaco,0,'cm29_i_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10376,'','".AddSlashes(pg_result($resaco,0,'cm29_d_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10377,'','".AddSlashes(pg_result($resaco,0,'cm29_t_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10378,'','".AddSlashes(pg_result($resaco,0,'cm29_i_concessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10379,'','".AddSlashes(pg_result($resaco,0,'cm29_d_concessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10380,'','".AddSlashes(pg_result($resaco,0,'cm29_t_concessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10381,'','".AddSlashes(pg_result($resaco,0,'cm29_d_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10382,'','".AddSlashes(pg_result($resaco,0,'cm29_d_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1795,10383,'','".AddSlashes(pg_result($resaco,0,'cm29_d_pronto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update proprijazigo set ";
     $virgula = "";
     if(trim($this->cm29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_codigo"])){ 
       $sql  .= $virgula." cm29_i_codigo = $this->cm29_i_codigo ";
       $virgula = ",";
       if(trim($this->cm29_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm29_i_propricemit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_propricemit"])){ 
       $sql  .= $virgula." cm29_i_propricemit = $this->cm29_i_propricemit ";
       $virgula = ",";
       if(trim($this->cm29_i_propricemit) == null ){ 
         $this->erro_sql = " Campo Código Propricemit nao Informado.";
         $this->erro_campo = "cm29_i_propricemit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm29_i_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_termo"])){ 
       $sql  .= $virgula." cm29_i_termo = $this->cm29_i_termo ";
       $virgula = ",";
       if(trim($this->cm29_i_termo) == null ){ 
         $this->erro_sql = " Campo Numero Termo nao Informado.";
         $this->erro_campo = "cm29_i_termo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm29_d_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_termo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm29_d_termo_dia"] !="") ){ 
       $sql  .= $virgula." cm29_d_termo = '$this->cm29_d_termo' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_termo_dia"])){ 
         $sql  .= $virgula." cm29_d_termo = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm29_t_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_t_termo"])){ 
       $sql  .= $virgula." cm29_t_termo = '$this->cm29_t_termo' ";
       $virgula = ",";
     }
     if(trim($this->cm29_i_concessao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_concessao"])){ 
       $sql  .= $virgula." cm29_i_concessao = $this->cm29_i_concessao ";
       $virgula = ",";
       if(trim($this->cm29_i_concessao) == null ){ 
         $this->erro_sql = " Campo Numero da Concessão nao Informado.";
         $this->erro_campo = "cm29_i_concessao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm29_d_concessao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao_dia"] !="") ){ 
       $sql  .= $virgula." cm29_d_concessao = '$this->cm29_d_concessao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao_dia"])){ 
         $sql  .= $virgula." cm29_d_concessao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm29_t_concessao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_t_concessao"])){ 
       $sql  .= $virgula." cm29_t_concessao = '$this->cm29_t_concessao' ";
       $virgula = ",";
     }
     if(trim($this->cm29_d_estrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura_dia"] !="") ){ 
       $sql  .= $virgula." cm29_d_estrutura = '$this->cm29_d_estrutura' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura_dia"])){ 
         $sql  .= $virgula." cm29_d_estrutura = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm29_d_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_base_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm29_d_base_dia"] !="") ){ 
       $sql  .= $virgula." cm29_d_base = '$this->cm29_d_base' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_base_dia"])){ 
         $sql  .= $virgula." cm29_d_base = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm29_d_pronto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto_dia"] !="") ){ 
       $sql  .= $virgula." cm29_d_pronto = '$this->cm29_d_pronto' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto_dia"])){ 
         $sql  .= $virgula." cm29_d_pronto = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($cm29_i_codigo!=null){
       $sql .= " cm29_i_codigo = $this->cm29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10373,'$this->cm29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1795,10373,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_i_codigo'))."','$this->cm29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_propricemit"]))
           $resac = db_query("insert into db_acount values($acount,1795,10374,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_i_propricemit'))."','$this->cm29_i_propricemit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_termo"]))
           $resac = db_query("insert into db_acount values($acount,1795,10375,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_i_termo'))."','$this->cm29_i_termo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_termo"]))
           $resac = db_query("insert into db_acount values($acount,1795,10376,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_d_termo'))."','$this->cm29_d_termo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_t_termo"]))
           $resac = db_query("insert into db_acount values($acount,1795,10377,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_t_termo'))."','$this->cm29_t_termo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_i_concessao"]))
           $resac = db_query("insert into db_acount values($acount,1795,10378,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_i_concessao'))."','$this->cm29_i_concessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_concessao"]))
           $resac = db_query("insert into db_acount values($acount,1795,10379,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_d_concessao'))."','$this->cm29_d_concessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_t_concessao"]))
           $resac = db_query("insert into db_acount values($acount,1795,10380,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_t_concessao'))."','$this->cm29_t_concessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_estrutura"]))
           $resac = db_query("insert into db_acount values($acount,1795,10381,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_d_estrutura'))."','$this->cm29_d_estrutura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_base"]))
           $resac = db_query("insert into db_acount values($acount,1795,10382,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_d_base'))."','$this->cm29_d_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm29_d_pronto"]))
           $resac = db_query("insert into db_acount values($acount,1795,10383,'".AddSlashes(pg_result($resaco,$conresaco,'cm29_d_pronto'))."','$this->cm29_d_pronto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Proprietário do Jazigo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Proprietário do Jazigo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10373,'$cm29_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1795,10373,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10374,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_i_propricemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10375,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_i_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10376,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_d_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10377,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_t_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10378,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_i_concessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10379,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_d_concessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10380,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_t_concessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10381,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_d_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10382,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_d_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1795,10383,'','".AddSlashes(pg_result($resaco,$iresaco,'cm29_d_pronto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from proprijazigo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm29_i_codigo = $cm29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Proprietário do Jazigo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Proprietário do Jazigo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm29_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:proprijazigo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proprijazigo ";
     $sql .= "      inner join propricemit  on  propricemit.cm28_i_codigo = proprijazigo.cm29_i_propricemit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = propricemit.cm28_i_proprietario";
     $sql .= "      left join protprocesso  on  protprocesso.p58_codproc = propricemit.cm28_i_processo";
     $sql .= "      inner join ossoariojazigo  on  ossoariojazigo.cm25_i_codigo = propricemit.cm28_i_ossoariojazigo";
     $sql2 = "";
     if($dbwhere==""){
       if($cm29_i_codigo!=null ){
         $sql2 .= " where proprijazigo.cm29_i_codigo = $cm29_i_codigo "; 
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
   function sql_query_file ( $cm29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proprijazigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm29_i_codigo!=null ){
         $sql2 .= " where proprijazigo.cm29_i_codigo = $cm29_i_codigo "; 
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