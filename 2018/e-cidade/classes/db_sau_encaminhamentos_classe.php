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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_encaminhamentos
class cl_sau_encaminhamentos { 
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
   var $s142_i_codigo = 0; 
   var $s142_i_profissional = 0; 
   var $s142_i_unidade = 0; 
   var $s142_i_cgsund = 0; 
   var $s142_i_prontuario = 0; 
   var $s142_i_rhcbo = 0; 
   var $s142_i_prestadora = 0; 
   var $s142_i_tipo = 0; 
   var $s142_t_dadosclinicos = null; 
   var $s142_d_encaminhamento_dia = null; 
   var $s142_d_encaminhamento_mes = null; 
   var $s142_d_encaminhamento_ano = null; 
   var $s142_d_encaminhamento = null; 
   var $s142_d_retorno_dia = null; 
   var $s142_d_retorno_mes = null; 
   var $s142_d_retorno_ano = null; 
   var $s142_d_retorno = null; 
   var $s142_d_validade_dia = null; 
   var $s142_d_validade_mes = null; 
   var $s142_d_validade_ano = null; 
   var $s142_d_validade = null; 
   var $s142_i_login = 0; 
   var $s142_i_profsolicitante = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s142_i_codigo = int4 = Código 
                 s142_i_profissional = int4 = profissional 
                 s142_i_unidade = int4 = Unidade 
                 s142_i_cgsund = int4 = cgs 
                 s142_i_prontuario = int4 = FAA 
                 s142_i_rhcbo = int4 = especialidade 
                 s142_i_prestadora = int4 = Prestadora 
                 s142_i_tipo = int4 = Tipo 
                 s142_t_dadosclinicos = text = Dados Clínicos 
                 s142_d_encaminhamento = date = Data do Encaminhamento 
                 s142_d_retorno = date = Data de Retorno 
                 s142_d_validade = date = Data de Validade 
                 s142_i_login = int4 = Login 
                 s142_i_profsolicitante = int4 = Solicitante 
                 ";
   //funcao construtor da classe 
   function cl_sau_encaminhamentos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_encaminhamentos"); 
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
       $this->s142_i_codigo = ($this->s142_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_codigo"]:$this->s142_i_codigo);
       $this->s142_i_profissional = ($this->s142_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"]:$this->s142_i_profissional);
       $this->s142_i_unidade = ($this->s142_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"]:$this->s142_i_unidade);
       $this->s142_i_cgsund = ($this->s142_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_cgsund"]:$this->s142_i_cgsund);
       $this->s142_i_prontuario = ($this->s142_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"]:$this->s142_i_prontuario);
       $this->s142_i_rhcbo = ($this->s142_i_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_rhcbo"]:$this->s142_i_rhcbo);
       $this->s142_i_prestadora = ($this->s142_i_prestadora == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"]:$this->s142_i_prestadora);
       $this->s142_i_tipo = ($this->s142_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_tipo"]:$this->s142_i_tipo);
       $this->s142_t_dadosclinicos = ($this->s142_t_dadosclinicos == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_t_dadosclinicos"]:$this->s142_t_dadosclinicos);
       if($this->s142_d_encaminhamento == ""){
         $this->s142_d_encaminhamento_dia = ($this->s142_d_encaminhamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"]:$this->s142_d_encaminhamento_dia);
         $this->s142_d_encaminhamento_mes = ($this->s142_d_encaminhamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_mes"]:$this->s142_d_encaminhamento_mes);
         $this->s142_d_encaminhamento_ano = ($this->s142_d_encaminhamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_ano"]:$this->s142_d_encaminhamento_ano);
         if($this->s142_d_encaminhamento_dia != ""){
            $this->s142_d_encaminhamento = $this->s142_d_encaminhamento_ano."-".$this->s142_d_encaminhamento_mes."-".$this->s142_d_encaminhamento_dia;
         }
       }
       if($this->s142_d_retorno == ""){
         $this->s142_d_retorno_dia = ($this->s142_d_retorno_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"]:$this->s142_d_retorno_dia);
         $this->s142_d_retorno_mes = ($this->s142_d_retorno_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_mes"]:$this->s142_d_retorno_mes);
         $this->s142_d_retorno_ano = ($this->s142_d_retorno_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_ano"]:$this->s142_d_retorno_ano);
         if($this->s142_d_retorno_dia != ""){
            $this->s142_d_retorno = $this->s142_d_retorno_ano."-".$this->s142_d_retorno_mes."-".$this->s142_d_retorno_dia;
         }
       }
       if($this->s142_d_validade == ""){
         $this->s142_d_validade_dia = ($this->s142_d_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"]:$this->s142_d_validade_dia);
         $this->s142_d_validade_mes = ($this->s142_d_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_validade_mes"]:$this->s142_d_validade_mes);
         $this->s142_d_validade_ano = ($this->s142_d_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_d_validade_ano"]:$this->s142_d_validade_ano);
         if($this->s142_d_validade_dia != ""){
            $this->s142_d_validade = $this->s142_d_validade_ano."-".$this->s142_d_validade_mes."-".$this->s142_d_validade_dia;
         }
       }
       $this->s142_i_login = ($this->s142_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_login"]:$this->s142_i_login);
       $this->s142_i_profsolicitante = ($this->s142_i_profsolicitante == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_profsolicitante"]:$this->s142_i_profsolicitante);
     }else{
       $this->s142_i_codigo = ($this->s142_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s142_i_codigo"]:$this->s142_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s142_i_codigo){ 
      $this->atualizacampos();
     if($this->s142_i_profissional == null ){ 
       $this->s142_i_profissional = "null";
     }
     if($this->s142_i_unidade == null ){ 
       $this->s142_i_unidade = "null";
     }
     if($this->s142_i_cgsund == null ){ 
       $this->erro_sql = " Campo cgs nao Informado.";
       $this->erro_campo = "s142_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_i_prontuario == null ){ 
       $this->s142_i_prontuario = "null";
     }
     if($this->s142_i_rhcbo == null ){ 
       $this->erro_sql = " Campo especialidade nao Informado.";
       $this->erro_campo = "s142_i_rhcbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_i_prestadora == null ){ 
       $this->s142_i_prestadora = "null";
     }
     if($this->s142_i_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "s142_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_d_encaminhamento == null ){ 
       $this->erro_sql = " Campo Data do Encaminhamento nao Informado.";
       $this->erro_campo = "s142_d_encaminhamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_d_retorno == null ){ 
       $this->erro_sql = " Campo Data de Retorno nao Informado.";
       $this->erro_campo = "s142_d_retorno_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_d_validade == null ){ 
       $this->erro_sql = " Campo Data de Validade nao Informado.";
       $this->erro_campo = "s142_d_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "s142_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s142_i_profsolicitante == null ){ 
       $this->s142_i_profsolicitante = "0";
     }
     if($s142_i_codigo == "" || $s142_i_codigo == null ){
       $result = db_query("select nextval('sau_encaminhamentos_s142_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_encaminhamentos_s142_i_codigo_seq do campo: s142_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s142_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_encaminhamentos_s142_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s142_i_codigo)){
         $this->erro_sql = " Campo s142_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s142_i_codigo = $s142_i_codigo; 
       }
     }
     if(($this->s142_i_codigo == null) || ($this->s142_i_codigo == "") ){ 
       $this->erro_sql = " Campo s142_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_encaminhamentos(
                                       s142_i_codigo 
                                      ,s142_i_profissional 
                                      ,s142_i_unidade 
                                      ,s142_i_cgsund 
                                      ,s142_i_prontuario 
                                      ,s142_i_rhcbo 
                                      ,s142_i_prestadora 
                                      ,s142_i_tipo 
                                      ,s142_t_dadosclinicos 
                                      ,s142_d_encaminhamento 
                                      ,s142_d_retorno 
                                      ,s142_d_validade 
                                      ,s142_i_login 
                                      ,s142_i_profsolicitante 
                       )
                values (
                                $this->s142_i_codigo 
                               ,$this->s142_i_profissional 
                               ,$this->s142_i_unidade 
                               ,$this->s142_i_cgsund 
                               ,$this->s142_i_prontuario 
                               ,$this->s142_i_rhcbo 
                               ,$this->s142_i_prestadora 
                               ,$this->s142_i_tipo 
                               ,'$this->s142_t_dadosclinicos' 
                               ,".($this->s142_d_encaminhamento == "null" || $this->s142_d_encaminhamento == ""?"null":"'".$this->s142_d_encaminhamento."'")." 
                               ,".($this->s142_d_retorno == "null" || $this->s142_d_retorno == ""?"null":"'".$this->s142_d_retorno."'")." 
                               ,".($this->s142_d_validade == "null" || $this->s142_d_validade == ""?"null":"'".$this->s142_d_validade."'")." 
                               ,$this->s142_i_login 
                               ,$this->s142_i_profsolicitante 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_encaminhamentos ($this->s142_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_encaminhamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_encaminhamentos ($this->s142_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s142_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15489,'$this->s142_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2717,15489,'','".AddSlashes(pg_result($resaco,0,'s142_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15491,'','".AddSlashes(pg_result($resaco,0,'s142_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15495,'','".AddSlashes(pg_result($resaco,0,'s142_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15490,'','".AddSlashes(pg_result($resaco,0,'s142_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15492,'','".AddSlashes(pg_result($resaco,0,'s142_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15493,'','".AddSlashes(pg_result($resaco,0,'s142_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15494,'','".AddSlashes(pg_result($resaco,0,'s142_i_prestadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15496,'','".AddSlashes(pg_result($resaco,0,'s142_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15497,'','".AddSlashes(pg_result($resaco,0,'s142_t_dadosclinicos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15498,'','".AddSlashes(pg_result($resaco,0,'s142_d_encaminhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15499,'','".AddSlashes(pg_result($resaco,0,'s142_d_retorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15500,'','".AddSlashes(pg_result($resaco,0,'s142_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15598,'','".AddSlashes(pg_result($resaco,0,'s142_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2717,15648,'','".AddSlashes(pg_result($resaco,0,'s142_i_profsolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s142_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_encaminhamentos set ";
     $virgula = "";
     if(trim($this->s142_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_codigo"])){ 
       $sql  .= $virgula." s142_i_codigo = $this->s142_i_codigo ";
       $virgula = ",";
       if(trim($this->s142_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s142_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"])){ 
        if(trim($this->s142_i_profissional)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"])){ 
           $this->s142_i_profissional = "0" ; 
        } 
       $sql  .= $virgula." s142_i_profissional = $this->s142_i_profissional ";
       $virgula = ",";
     }
     if(trim($this->s142_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"])){ 
        if(trim($this->s142_i_unidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"])){ 
           $this->s142_i_unidade = "0" ; 
        } 
       $sql  .= $virgula." s142_i_unidade = $this->s142_i_unidade ";
       $virgula = ",";
     }
     if(trim($this->s142_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_cgsund"])){ 
       $sql  .= $virgula." s142_i_cgsund = $this->s142_i_cgsund ";
       $virgula = ",";
       if(trim($this->s142_i_cgsund) == null ){ 
         $this->erro_sql = " Campo cgs nao Informado.";
         $this->erro_campo = "s142_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"])){ 
        if(trim($this->s142_i_prontuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"])){ 
           $this->s142_i_prontuario = "0" ; 
        } 
       $sql  .= $virgula." s142_i_prontuario = $this->s142_i_prontuario ";
       $virgula = ",";
     }
     if(trim($this->s142_i_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_rhcbo"])){ 
       $sql  .= $virgula." s142_i_rhcbo = $this->s142_i_rhcbo ";
       $virgula = ",";
       if(trim($this->s142_i_rhcbo) == null ){ 
         $this->erro_sql = " Campo especialidade nao Informado.";
         $this->erro_campo = "s142_i_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_prestadora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"])){ 
        if(trim($this->s142_i_prestadora)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"])){ 
           $this->s142_i_prestadora = "0" ; 
        } 
       $sql  .= $virgula." s142_i_prestadora = $this->s142_i_prestadora ";
       $virgula = ",";
     }
     if(trim($this->s142_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_tipo"])){ 
       $sql  .= $virgula." s142_i_tipo = $this->s142_i_tipo ";
       $virgula = ",";
       if(trim($this->s142_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "s142_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_t_dadosclinicos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_t_dadosclinicos"])){ 
       $sql  .= $virgula." s142_t_dadosclinicos = '$this->s142_t_dadosclinicos' ";
       $virgula = ",";
     }
     if(trim($this->s142_d_encaminhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"] !="") ){ 
       $sql  .= $virgula." s142_d_encaminhamento = '$this->s142_d_encaminhamento' ";
       $virgula = ",";
       if(trim($this->s142_d_encaminhamento) == null ){ 
         $this->erro_sql = " Campo Data do Encaminhamento nao Informado.";
         $this->erro_campo = "s142_d_encaminhamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"])){ 
         $sql  .= $virgula." s142_d_encaminhamento = null ";
         $virgula = ",";
         if(trim($this->s142_d_encaminhamento) == null ){ 
           $this->erro_sql = " Campo Data do Encaminhamento nao Informado.";
           $this->erro_campo = "s142_d_encaminhamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s142_d_retorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"] !="") ){ 
       $sql  .= $virgula." s142_d_retorno = '$this->s142_d_retorno' ";
       $virgula = ",";
       if(trim($this->s142_d_retorno) == null ){ 
         $this->erro_sql = " Campo Data de Retorno nao Informado.";
         $this->erro_campo = "s142_d_retorno_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"])){ 
         $sql  .= $virgula." s142_d_retorno = null ";
         $virgula = ",";
         if(trim($this->s142_d_retorno) == null ){ 
           $this->erro_sql = " Campo Data de Retorno nao Informado.";
           $this->erro_campo = "s142_d_retorno_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s142_d_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"] !="") ){ 
       $sql  .= $virgula." s142_d_validade = '$this->s142_d_validade' ";
       $virgula = ",";
       if(trim($this->s142_d_validade) == null ){ 
         $this->erro_sql = " Campo Data de Validade nao Informado.";
         $this->erro_campo = "s142_d_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"])){ 
         $sql  .= $virgula." s142_d_validade = null ";
         $virgula = ",";
         if(trim($this->s142_d_validade) == null ){ 
           $this->erro_sql = " Campo Data de Validade nao Informado.";
           $this->erro_campo = "s142_d_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s142_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_login"])){ 
       $sql  .= $virgula." s142_i_login = $this->s142_i_login ";
       $virgula = ",";
       if(trim($this->s142_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "s142_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_profsolicitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profsolicitante"])){ 
        if(trim($this->s142_i_profsolicitante)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profsolicitante"])){ 
           $this->s142_i_profsolicitante = "0" ; 
        } 
       $sql  .= $virgula." s142_i_profsolicitante = $this->s142_i_profsolicitante ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($s142_i_codigo!=null){
       $sql .= " s142_i_codigo = $this->s142_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s142_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15489,'$this->s142_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_codigo"]) || $this->s142_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2717,15489,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_codigo'))."','$this->s142_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"]) || $this->s142_i_profissional != "")
           $resac = db_query("insert into db_acount values($acount,2717,15491,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_profissional'))."','$this->s142_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"]) || $this->s142_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2717,15495,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_unidade'))."','$this->s142_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_cgsund"]) || $this->s142_i_cgsund != "")
           $resac = db_query("insert into db_acount values($acount,2717,15490,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_cgsund'))."','$this->s142_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"]) || $this->s142_i_prontuario != "")
           $resac = db_query("insert into db_acount values($acount,2717,15492,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_prontuario'))."','$this->s142_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_rhcbo"]) || $this->s142_i_rhcbo != "")
           $resac = db_query("insert into db_acount values($acount,2717,15493,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_rhcbo'))."','$this->s142_i_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"]) || $this->s142_i_prestadora != "")
           $resac = db_query("insert into db_acount values($acount,2717,15494,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_prestadora'))."','$this->s142_i_prestadora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_tipo"]) || $this->s142_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2717,15496,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_tipo'))."','$this->s142_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_t_dadosclinicos"]) || $this->s142_t_dadosclinicos != "")
           $resac = db_query("insert into db_acount values($acount,2717,15497,'".AddSlashes(pg_result($resaco,$conresaco,'s142_t_dadosclinicos'))."','$this->s142_t_dadosclinicos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento"]) || $this->s142_d_encaminhamento != "")
           $resac = db_query("insert into db_acount values($acount,2717,15498,'".AddSlashes(pg_result($resaco,$conresaco,'s142_d_encaminhamento'))."','$this->s142_d_encaminhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno"]) || $this->s142_d_retorno != "")
           $resac = db_query("insert into db_acount values($acount,2717,15499,'".AddSlashes(pg_result($resaco,$conresaco,'s142_d_retorno'))."','$this->s142_d_retorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_validade"]) || $this->s142_d_validade != "")
           $resac = db_query("insert into db_acount values($acount,2717,15500,'".AddSlashes(pg_result($resaco,$conresaco,'s142_d_validade'))."','$this->s142_d_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_login"]) || $this->s142_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2717,15598,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_login'))."','$this->s142_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profsolicitante"]) || $this->s142_i_profsolicitante != "")
           $resac = db_query("insert into db_acount values($acount,2717,15648,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_profsolicitante')).
           "','$this->s142_i_profsolicitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_encaminhamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_encaminhamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s142_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s142_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15489,'$s142_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2717,15489,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15491,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15495,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15490,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15492,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15493,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15494,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_prestadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15496,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15497,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_t_dadosclinicos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15498,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_d_encaminhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15499,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_d_retorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15500,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_d_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15598,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2717,15648,'','".AddSlashes(pg_result($resaco,$iresaco,'s142_i_profsolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_encaminhamentos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s142_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s142_i_codigo = $s142_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_encaminhamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s142_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_encaminhamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s142_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s142_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_encaminhamentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s142_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_encaminhamentos ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_encaminhamentos.s142_i_login";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = sau_encaminhamentos.s142_i_rhcbo";
     $sql .= "      left  join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_encaminhamentos.s142_i_prestadora";
     $sql .= "      left  join unidades  on  unidades.sd02_i_codigo = sau_encaminhamentos.s142_i_unidade";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = sau_encaminhamentos.s142_i_profsolicitante and  medicos.sd03_i_codigo = sau_encaminhamentos.s142_i_profissional";
     $sql .= "      left  join prontuarios  on  prontuarios.sd24_i_codigo = sau_encaminhamentos.s142_i_prontuario";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_encaminhamentos.s142_i_cgsund";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sau_prestadores.s110_i_numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = unidades.sd02_i_numcgm and   a.z01_numcgm = unidades.sd02_i_diretor";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left  join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left  join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left  join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left  join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left  join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left  join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left  join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      inner join cgm  as b on   b.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join unidades  as c on   c.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  as d on   d.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as d on   d.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($s142_i_codigo!=null ){
         $sql2 .= " where sau_encaminhamentos.s142_i_codigo = $s142_i_codigo "; 
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
   function sql_query_file ( $s142_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_encaminhamentos ";
     $sql2 = "";
     if($dbwhere==""){
       if($s142_i_codigo!=null ){
         $sql2 .= " where sau_encaminhamentos.s142_i_codigo = $s142_i_codigo "; 
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
   /*
  * Gera o SQL para a busca dos encaminhamentos feitos para um profissional (filtra por unidade tambem)
  */
  function sql_query_encaminhamentos_profissional($s142_i_codigo = null, $campos = "", $iMedico, $iUnidade, 
                                                  $ordem = null, $dbwhere = "") { 
    $sql = "select ";
    if($campos != "" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sql .= "       sau_encaminhamentos.s142_i_codigo, ";
      $sql .= "       sau_encaminhamentos.s142_i_cgsund, ";
      $sql .= "       cgs_und.z01_v_nome, ";
      $sql .= "       sau_encaminhamentos.s142_i_profissional, ";
      $sql .= "       a.z01_nome, ";
      $sql .= "       sau_encaminhamentos.s142_i_prontuario, ";
      $sql .= "       case when sau_encaminhamentos.s142_i_tipo = 1 ";
      $sql .= "              then 'Consulta' ";
      $sql .= "            else ";
      $sql .= "              'Exame' ";
      $sql .= "       end as s142_i_tipo, ";
      $sql .= "       sau_encaminhamentos.s142_d_encaminhamento, ";
      $sql .= "       sau_encaminhamentos.s142_d_retorno, ";
      $sql .= "       sau_encaminhamentos.s142_d_validade, ";
      $sql .= "       sau_encaminhamentos.s142_i_unidade, ";
      $sql .= "       rhcbo.rh70_estrutural, ";
      $sql .= "       sau_encaminhamentos.s142_t_dadosclinicos, ";
      $sql .= "       sau_encaminhamentos.s142_i_prestadora, ";
      $sql .= "       sau_encaminhamentos.s142_i_profsolicitante, ";
      $sql .= "       b.z01_nome ";



    }
    $sql .= "  from sau_encaminhamentos ";
    $sql .= "    inner join rhcbo  on  rhcbo.rh70_sequencial = sau_encaminhamentos.s142_i_rhcbo";
    $sql .= "    left  join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_encaminhamentos.s142_i_prestadora";
    $sql .= "    left  join unidades  on  unidades.sd02_i_codigo = sau_encaminhamentos.s142_i_unidade";
    $sql .= "    left  join medicos  on  medicos.sd03_i_codigo = sau_encaminhamentos.s142_i_profissional";
    $sql .= "    left join prontuarios  on  prontuarios.sd24_i_codigo = sau_encaminhamentos.s142_i_prontuario";
    $sql .= "    inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_encaminhamentos.s142_i_cgsund";
    $sql .= "    left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
    $sql .= "    left  join cgm on cgm.z01_numcgm = sau_prestadores.s110_i_numcgm ";
    $sql .= "    left join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
    $sql .= "    left join cgm as a on a.z01_numcgm = medicos.sd03_i_cgm ";
    $sql .= "    inner join medicos as medicosolicitante on  medicosolicitante.sd03_i_codigo = sau_encaminhamentos.s142_i_profsolicitante";
    $sql .= "    inner join cgm as b on  b.z01_numcgm = medicosolicitante.sd03_i_cgm";
    $sql2 = "  where medicos.sd03_i_codigo = $iMedico and sd02_i_codigo = $iUnidade";
    if($dbwhere==""){
      if($s142_i_codigo!=null ){
        $sql2 .= " and sau_encaminhamentos.s142_i_codigo = $s142_i_codigo "; 
      } 
    }else if($dbwhere != ""){
      $sql2 .= " and $dbwhere";
    }
    $sql .= $sql2.' and s142_i_codigo not in (select s149_i_encaminhamento from sau_encaminhanulado)';
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
   function sql_query2 ( $s142_i_codigo=null,$campos="",$ordem=null,$dbwhere=""){ 
    $sql = "select ";
    if($campos != "" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sql .= "       sau_encaminhamentos.s142_i_codigo, ";
      $sql .= "       sau_encaminhamentos.s142_i_cgsund, ";
      $sql .= "       cgs_und.z01_v_nome, ";
      $sql .= "       sau_encaminhamentos.s142_i_profissional, ";
      $sql .= "       a.z01_nome, ";
      $sql .= "       sau_encaminhamentos.s142_i_prontuario, ";
      $sql .= "       case when sau_encaminhamentos.s142_i_tipo = 1 ";
      $sql .= "              then 'Consulta' ";
      $sql .= "            else ";
      $sql .= "              'Exame' ";
      $sql .= "       end as s142_i_tipo, ";
      $sql .= "       sau_encaminhamentos.s142_d_encaminhamento, ";
      $sql .= "       sau_encaminhamentos.s142_d_retorno, ";
      $sql .= "       sau_encaminhamentos.s142_d_validade, ";
      $sql .= "       sau_encaminhamentos.s142_i_unidade, ";
      $sql .= "       rhcbo.rh70_estrutural, ";
      $sql .= "       sau_encaminhamentos.s142_t_dadosclinicos, ";
      $sql .= "       sau_encaminhamentos.s142_i_prestadora, ";
      $sql .= "       sau_encaminhamentos.s142_i_profsolicitante, ";
      $sql .= "       b.z01_nome ";

     }
    $sql .= "  from sau_encaminhamentos ";
    $sql .= "    inner join rhcbo  on  rhcbo.rh70_sequencial = sau_encaminhamentos.s142_i_rhcbo";
    $sql .= "    left  join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_encaminhamentos.s142_i_prestadora";
    $sql .= "    left  join unidades  on  unidades.sd02_i_codigo = sau_encaminhamentos.s142_i_unidade";
    $sql .= "    left  join medicos  on  medicos.sd03_i_codigo = sau_encaminhamentos.s142_i_profissional";
    $sql .= "    left  join prontuarios  on  prontuarios.sd24_i_codigo = sau_encaminhamentos.s142_i_prontuario";
    $sql .= "    inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_encaminhamentos.s142_i_cgsund";
    $sql .= "    left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
    $sql .= "    left  join cgm on cgm.z01_numcgm = sau_prestadores.s110_i_numcgm ";
    $sql .= "    left  join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
    $sql .= "    left  join cgm as a on a.z01_numcgm = medicos.sd03_i_cgm ";
    $sql .= "    inner join medicos as medicosolicitante on  medicosolicitante.sd03_i_codigo = sau_encaminhamentos.s142_i_profsolicitante";
    $sql .= "    inner join cgm as b on  b.z01_numcgm = medicosolicitante.sd03_i_cgm";
    $sql2 = "  where s142_i_codigo not in (select s149_i_encaminhamento from sau_encaminhanulado) ";
    if($dbwhere==""){
      if($s142_i_codigo!=null ){
        $sql2 .= " and sau_encaminhamentos.s142_i_codigo = $s142_i_codigo "; 
      } 
    }else if($dbwhere != ""){
      $sql2 .= " and $dbwhere";
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

   // funcao para alteracao
   function alterar2 ($s142_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_encaminhamentos set ";
     $virgula = "";
     if(trim($this->s142_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_codigo"])){ 
       $sql  .= $virgula." s142_i_codigo = $this->s142_i_codigo ";
       $virgula = ",";
       if(trim($this->s142_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s142_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"])){ 
        if(trim($this->s142_i_profissional)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"])){ 
           $this->s142_i_profissional = "null" ; 
        } 
       $sql  .= $virgula." s142_i_profissional = $this->s142_i_profissional ";
       $virgula = ",";
     }
     if(trim($this->s142_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"])){ 
        if(trim($this->s142_i_unidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"])){ 
           $this->s142_i_unidade = "null" ; 
        } 
       $sql  .= $virgula." s142_i_unidade = $this->s142_i_unidade ";
       $virgula = ",";
     }
     if(trim($this->s142_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_cgsund"])){ 
       $sql  .= $virgula." s142_i_cgsund = $this->s142_i_cgsund ";
       $virgula = ",";
       if(trim($this->s142_i_cgsund) == null ){ 
         $this->erro_sql = " Campo cgs nao Informado.";
         $this->erro_campo = "s142_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"])){ 
        if(trim($this->s142_i_prontuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"])){ 
           $this->s142_i_prontuario = "null" ; 
        } 
       $sql  .= $virgula." s142_i_prontuario = $this->s142_i_prontuario ";
       $virgula = ",";
     }
     if(trim($this->s142_i_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_rhcbo"])){ 
       $sql  .= $virgula." s142_i_rhcbo = $this->s142_i_rhcbo ";
       $virgula = ",";
       if(trim($this->s142_i_rhcbo) == null ){ 
         $this->erro_sql = " Campo especialidade nao Informado.";
         $this->erro_campo = "s142_i_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_prestadora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"])){ 
        if(trim($this->s142_i_prestadora)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"])){ 
           $this->s142_i_prestadora = "null" ; 
        } 
       $sql  .= $virgula." s142_i_prestadora = $this->s142_i_prestadora ";
       $virgula = ",";
     }
     if(trim($this->s142_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_tipo"])){ 
       $sql  .= $virgula." s142_i_tipo = $this->s142_i_tipo ";
       $virgula = ",";
       if(trim($this->s142_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "s142_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_t_dadosclinicos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_t_dadosclinicos"])){ 
       $sql  .= $virgula." s142_t_dadosclinicos = '$this->s142_t_dadosclinicos' ";
       $virgula = ",";
     }
     if(trim($this->s142_d_encaminhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"] !="") ){ 
       $sql  .= $virgula." s142_d_encaminhamento = '$this->s142_d_encaminhamento' ";
       $virgula = ",";
       if(trim($this->s142_d_encaminhamento) == null ){ 
         $this->erro_sql = " Campo Data do Encaminhamento nao Informado.";
         $this->erro_campo = "s142_d_encaminhamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento_dia"])){ 
         $sql  .= $virgula." s142_d_encaminhamento = null ";
         $virgula = ",";
         if(trim($this->s142_d_encaminhamento) == null ){ 
           $this->erro_sql = " Campo Data do Encaminhamento nao Informado.";
           $this->erro_campo = "s142_d_encaminhamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s142_d_retorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"] !="") ){ 
       $sql  .= $virgula." s142_d_retorno = '$this->s142_d_retorno' ";
       $virgula = ",";
       if(trim($this->s142_d_retorno) == null ){ 
         $this->erro_sql = " Campo Data de Retorno nao Informado.";
         $this->erro_campo = "s142_d_retorno_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno_dia"])){ 
         $sql  .= $virgula." s142_d_retorno = null ";
         $virgula = ",";
         if(trim($this->s142_d_retorno) == null ){ 
           $this->erro_sql = " Campo Data de Retorno nao Informado.";
           $this->erro_campo = "s142_d_retorno_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s142_d_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"] !="") ){ 
       $sql  .= $virgula." s142_d_validade = '$this->s142_d_validade' ";
       $virgula = ",";
       if(trim($this->s142_d_validade) == null ){ 
         $this->erro_sql = " Campo Data de Validade nao Informado.";
         $this->erro_campo = "s142_d_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_validade_dia"])){ 
         $sql  .= $virgula." s142_d_validade = null ";
         $virgula = ",";
         if(trim($this->s142_d_validade) == null ){ 
           $this->erro_sql = " Campo Data de Validade nao Informado.";
           $this->erro_campo = "s142_d_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s142_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_login"])){ 
       $sql  .= $virgula." s142_i_login = $this->s142_i_login ";
       $virgula = ",";
       if(trim($this->s142_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "s142_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s142_i_profsolicitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profsolicitante"])){ 
       $sql  .= $virgula." s142_i_profsolicitante = $this->s142_i_profsolicitante";
       $virgula = ",";
       if(trim($this->s142_i_profsolicitante) == null ){ 
         $this->erro_sql = " Campo Solicitante nao Informado.";
         $this->erro_campo = "s142_i_profsolicitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s142_i_codigo!=null){
       $sql .= " s142_i_codigo = $this->s142_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s142_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15489,'$this->s142_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_codigo"]) || $this->s142_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2717,15489,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_codigo'))."','$this->s142_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profissional"]) || $this->s142_i_profissional != "")
           $resac = db_query("insert into db_acount values($acount,2717,15491,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_profissional'))."','$this->s142_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_unidade"]) || $this->s142_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2717,15495,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_unidade'))."','$this->s142_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_cgsund"]) || $this->s142_i_cgsund != "")
           $resac = db_query("insert into db_acount values($acount,2717,15490,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_cgsund'))."','$this->s142_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prontuario"]) || $this->s142_i_prontuario != "")
           $resac = db_query("insert into db_acount values($acount,2717,15492,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_prontuario'))."','$this->s142_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_rhcbo"]) || $this->s142_i_rhcbo != "")
           $resac = db_query("insert into db_acount values($acount,2717,15493,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_rhcbo'))."','$this->s142_i_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_prestadora"]) || $this->s142_i_prestadora != "")
           $resac = db_query("insert into db_acount values($acount,2717,15494,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_prestadora'))."','$this->s142_i_prestadora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_tipo"]) || $this->s142_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2717,15496,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_tipo'))."','$this->s142_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_t_dadosclinicos"]) || $this->s142_t_dadosclinicos != "")
           $resac = db_query("insert into db_acount values($acount,2717,15497,'".AddSlashes(pg_result($resaco,$conresaco,'s142_t_dadosclinicos'))."','$this->s142_t_dadosclinicos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_encaminhamento"]) || $this->s142_d_encaminhamento != "")
           $resac = db_query("insert into db_acount values($acount,2717,15498,'".AddSlashes(pg_result($resaco,$conresaco,'s142_d_encaminhamento'))."','$this->s142_d_encaminhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_retorno"]) || $this->s142_d_retorno != "")
           $resac = db_query("insert into db_acount values($acount,2717,15499,'".AddSlashes(pg_result($resaco,$conresaco,'s142_d_retorno'))."','$this->s142_d_retorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_d_validade"]) || $this->s142_d_validade != "")
           $resac = db_query("insert into db_acount values($acount,2717,15500,'".AddSlashes(pg_result($resaco,$conresaco,'s142_d_validade'))."','$this->s142_d_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_login"]) || $this->s142_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2717,15598,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_login'))."','$this->s142_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s142_i_profsolicitante"]) || $this->s142_i_profsolicitante != "")
           $resac = db_query("insert into db_acount values($acount,2717,15648,'".AddSlashes(pg_result($resaco,$conresaco,'s142_i_profsolicitante')).
           "','$this->s142_i_profsolicitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_encaminhamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_encaminhamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s142_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
}
?>