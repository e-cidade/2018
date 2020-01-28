<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE atolegal
class cl_atolegal { 
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
   var $ed05_i_codigo = 0; 
   var $ed05_c_numero = 0; 
   var $ed05_c_finalidade = null; 
   var $ed05_i_tipoato = 0; 
   var $ed05_c_competencia = null; 
   var $ed05_i_ano = 0; 
   var $ed05_c_orgao = null; 
   var $ed05_d_vigora_dia = null; 
   var $ed05_d_vigora_mes = null; 
   var $ed05_d_vigora_ano = null; 
   var $ed05_d_vigora = null; 
   var $ed05_d_aprovado_dia = null; 
   var $ed05_d_aprovado_mes = null; 
   var $ed05_d_aprovado_ano = null; 
   var $ed05_d_aprovado = null; 
   var $ed05_d_publicado_dia = null; 
   var $ed05_d_publicado_mes = null; 
   var $ed05_d_publicado_ano = null; 
   var $ed05_d_publicado = null; 
   var $ed05_t_texto = null; 
   var $ed05_i_aparecehistorico = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed05_i_codigo = int8 = Código do Ato 
                 ed05_c_numero = int4 = Número 
                 ed05_c_finalidade = char(50) = Finalidade 
                 ed05_i_tipoato = int8 = Tipo 
                 ed05_c_competencia = char(1) = Competência 
                 ed05_i_ano = int4 = Ano 
                 ed05_c_orgao = char(50) = Órgão Emitente 
                 ed05_d_vigora = date = Vigência 
                 ed05_d_aprovado = date = Aprovação 
                 ed05_d_publicado = date = Publicação 
                 ed05_t_texto = text = Texto do Ato 
                 ed05_i_aparecehistorico = int4 = Aparece no Histórico 
                 ";
   //funcao construtor da classe 
   function cl_atolegal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atolegal"); 
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
       $this->ed05_i_codigo = ($this->ed05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"]:$this->ed05_i_codigo);
       $this->ed05_c_numero = ($this->ed05_c_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_c_numero"]:$this->ed05_c_numero);
       $this->ed05_c_finalidade = ($this->ed05_c_finalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_c_finalidade"]:$this->ed05_c_finalidade);
       $this->ed05_i_tipoato = ($this->ed05_i_tipoato == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_tipoato"]:$this->ed05_i_tipoato);
       $this->ed05_c_competencia = ($this->ed05_c_competencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_c_competencia"]:$this->ed05_c_competencia);
       $this->ed05_i_ano = ($this->ed05_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_ano"]:$this->ed05_i_ano);
       $this->ed05_c_orgao = ($this->ed05_c_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_c_orgao"]:$this->ed05_c_orgao);
       if($this->ed05_d_vigora == ""){
         $this->ed05_d_vigora_dia = ($this->ed05_d_vigora_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora_dia"]:$this->ed05_d_vigora_dia);
         $this->ed05_d_vigora_mes = ($this->ed05_d_vigora_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora_mes"]:$this->ed05_d_vigora_mes);
         $this->ed05_d_vigora_ano = ($this->ed05_d_vigora_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora_ano"]:$this->ed05_d_vigora_ano);
         if($this->ed05_d_vigora_dia != ""){
            $this->ed05_d_vigora = $this->ed05_d_vigora_ano."-".$this->ed05_d_vigora_mes."-".$this->ed05_d_vigora_dia;
         }
       }
       if($this->ed05_d_aprovado == ""){
         $this->ed05_d_aprovado_dia = ($this->ed05_d_aprovado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado_dia"]:$this->ed05_d_aprovado_dia);
         $this->ed05_d_aprovado_mes = ($this->ed05_d_aprovado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado_mes"]:$this->ed05_d_aprovado_mes);
         $this->ed05_d_aprovado_ano = ($this->ed05_d_aprovado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado_ano"]:$this->ed05_d_aprovado_ano);
         if($this->ed05_d_aprovado_dia != ""){
            $this->ed05_d_aprovado = $this->ed05_d_aprovado_ano."-".$this->ed05_d_aprovado_mes."-".$this->ed05_d_aprovado_dia;
         }
       }
       if($this->ed05_d_publicado == ""){
         $this->ed05_d_publicado_dia = ($this->ed05_d_publicado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado_dia"]:$this->ed05_d_publicado_dia);
         $this->ed05_d_publicado_mes = ($this->ed05_d_publicado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado_mes"]:$this->ed05_d_publicado_mes);
         $this->ed05_d_publicado_ano = ($this->ed05_d_publicado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado_ano"]:$this->ed05_d_publicado_ano);
         if($this->ed05_d_publicado_dia != ""){
            $this->ed05_d_publicado = $this->ed05_d_publicado_ano."-".$this->ed05_d_publicado_mes."-".$this->ed05_d_publicado_dia;
         }
       }
       $this->ed05_t_texto = ($this->ed05_t_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_t_texto"]:$this->ed05_t_texto);
       $this->ed05_i_aparecehistorico = ($this->ed05_i_aparecehistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_aparecehistorico"]:$this->ed05_i_aparecehistorico);
     }else{
       $this->ed05_i_codigo = ($this->ed05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"]:$this->ed05_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed05_i_codigo){ 
      $this->atualizacampos();
     if($this->ed05_c_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "ed05_c_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_c_finalidade == null ){ 
       $this->erro_sql = " Campo Finalidade nao Informado.";
       $this->erro_campo = "ed05_c_finalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_i_tipoato == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "ed05_i_tipoato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_c_competencia == null ){ 
       $this->erro_sql = " Campo Competência nao Informado.";
       $this->erro_campo = "ed05_c_competencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_i_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "ed05_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_c_orgao == null ){ 
       $this->erro_sql = " Campo Órgão Emitente nao Informado.";
       $this->erro_campo = "ed05_c_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_d_vigora == null ){ 
       $this->erro_sql = " Campo Vigência nao Informado.";
       $this->erro_campo = "ed05_d_vigora_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_d_aprovado == null ){ 
       $this->erro_sql = " Campo Aprovação nao Informado.";
       $this->erro_campo = "ed05_d_aprovado_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_d_publicado == null ){ 
       $this->erro_sql = " Campo Publicação nao Informado.";
       $this->erro_campo = "ed05_d_publicado_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_i_aparecehistorico == null ){ 
       $this->erro_sql = " Campo Aparece no Histórico nao Informado.";
       $this->erro_campo = "ed05_i_aparecehistorico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed05_i_codigo == "" || $ed05_i_codigo == null ){
       $result = db_query("select nextval('atolegal_ed05_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atolegal_ed05_i_codigo_seq do campo: ed05_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed05_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atolegal_ed05_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed05_i_codigo)){
         $this->erro_sql = " Campo ed05_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed05_i_codigo = $ed05_i_codigo; 
       }
     }
     if(($this->ed05_i_codigo == null) || ($this->ed05_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed05_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atolegal(
                                       ed05_i_codigo 
                                      ,ed05_c_numero 
                                      ,ed05_c_finalidade 
                                      ,ed05_i_tipoato 
                                      ,ed05_c_competencia 
                                      ,ed05_i_ano 
                                      ,ed05_c_orgao 
                                      ,ed05_d_vigora 
                                      ,ed05_d_aprovado 
                                      ,ed05_d_publicado 
                                      ,ed05_t_texto 
                                      ,ed05_i_aparecehistorico 
                       )
                values (
                                $this->ed05_i_codigo 
                               ,$this->ed05_c_numero 
                               ,'$this->ed05_c_finalidade' 
                               ,$this->ed05_i_tipoato 
                               ,'$this->ed05_c_competencia' 
                               ,$this->ed05_i_ano 
                               ,'$this->ed05_c_orgao' 
                               ,".($this->ed05_d_vigora == "null" || $this->ed05_d_vigora == ""?"null":"'".$this->ed05_d_vigora."'")." 
                               ,".($this->ed05_d_aprovado == "null" || $this->ed05_d_aprovado == ""?"null":"'".$this->ed05_d_aprovado."'")." 
                               ,".($this->ed05_d_publicado == "null" || $this->ed05_d_publicado == ""?"null":"'".$this->ed05_d_publicado."'")." 
                               ,'$this->ed05_t_texto' 
                               ,$this->ed05_i_aparecehistorico 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ato Legal ($this->ed05_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ato Legal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ato Legal ($this->ed05_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed05_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008212,'$this->ed05_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010033,1008212,'','".AddSlashes(pg_result($resaco,0,'ed05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008215,'','".AddSlashes(pg_result($resaco,0,'ed05_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008217,'','".AddSlashes(pg_result($resaco,0,'ed05_c_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008213,'','".AddSlashes(pg_result($resaco,0,'ed05_i_tipoato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008214,'','".AddSlashes(pg_result($resaco,0,'ed05_c_competencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008216,'','".AddSlashes(pg_result($resaco,0,'ed05_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008218,'','".AddSlashes(pg_result($resaco,0,'ed05_c_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008219,'','".AddSlashes(pg_result($resaco,0,'ed05_d_vigora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008220,'','".AddSlashes(pg_result($resaco,0,'ed05_d_aprovado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008221,'','".AddSlashes(pg_result($resaco,0,'ed05_d_publicado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,1008222,'','".AddSlashes(pg_result($resaco,0,'ed05_t_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010033,17819,'','".AddSlashes(pg_result($resaco,0,'ed05_i_aparecehistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed05_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update atolegal set ";
     $virgula = "";
     if(trim($this->ed05_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"])){ 
       $sql  .= $virgula." ed05_i_codigo = $this->ed05_i_codigo ";
       $virgula = ",";
       if(trim($this->ed05_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Ato nao Informado.";
         $this->erro_campo = "ed05_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_c_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_numero"])){ 
       $sql  .= $virgula." ed05_c_numero = $this->ed05_c_numero ";
       $virgula = ",";
       if(trim($this->ed05_c_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "ed05_c_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_c_finalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_finalidade"])){ 
       $sql  .= $virgula." ed05_c_finalidade = '$this->ed05_c_finalidade' ";
       $virgula = ",";
       if(trim($this->ed05_c_finalidade) == null ){ 
         $this->erro_sql = " Campo Finalidade nao Informado.";
         $this->erro_campo = "ed05_c_finalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_i_tipoato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_tipoato"])){ 
       $sql  .= $virgula." ed05_i_tipoato = $this->ed05_i_tipoato ";
       $virgula = ",";
       if(trim($this->ed05_i_tipoato) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "ed05_i_tipoato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_c_competencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_competencia"])){ 
       $sql  .= $virgula." ed05_c_competencia = '$this->ed05_c_competencia' ";
       $virgula = ",";
       if(trim($this->ed05_c_competencia) == null ){ 
         $this->erro_sql = " Campo Competência nao Informado.";
         $this->erro_campo = "ed05_c_competencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_ano"])){ 
       $sql  .= $virgula." ed05_i_ano = $this->ed05_i_ano ";
       $virgula = ",";
       if(trim($this->ed05_i_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "ed05_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_c_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_orgao"])){ 
       $sql  .= $virgula." ed05_c_orgao = '$this->ed05_c_orgao' ";
       $virgula = ",";
       if(trim($this->ed05_c_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão Emitente nao Informado.";
         $this->erro_campo = "ed05_c_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_d_vigora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora_dia"] !="") ){ 
       $sql  .= $virgula." ed05_d_vigora = '$this->ed05_d_vigora' ";
       $virgula = ",";
       if(trim($this->ed05_d_vigora) == null ){ 
         $this->erro_sql = " Campo Vigência nao Informado.";
         $this->erro_campo = "ed05_d_vigora_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora_dia"])){ 
         $sql  .= $virgula." ed05_d_vigora = null ";
         $virgula = ",";
         if(trim($this->ed05_d_vigora) == null ){ 
           $this->erro_sql = " Campo Vigência nao Informado.";
           $this->erro_campo = "ed05_d_vigora_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed05_d_aprovado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado_dia"] !="") ){ 
       $sql  .= $virgula." ed05_d_aprovado = '$this->ed05_d_aprovado' ";
       $virgula = ",";
       if(trim($this->ed05_d_aprovado) == null ){ 
         $this->erro_sql = " Campo Aprovação nao Informado.";
         $this->erro_campo = "ed05_d_aprovado_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado_dia"])){ 
         $sql  .= $virgula." ed05_d_aprovado = null ";
         $virgula = ",";
         if(trim($this->ed05_d_aprovado) == null ){ 
           $this->erro_sql = " Campo Aprovação nao Informado.";
           $this->erro_campo = "ed05_d_aprovado_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed05_d_publicado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado_dia"] !="") ){ 
       $sql  .= $virgula." ed05_d_publicado = '$this->ed05_d_publicado' ";
       $virgula = ",";
       if(trim($this->ed05_d_publicado) == null ){ 
         $this->erro_sql = " Campo Publicação nao Informado.";
         $this->erro_campo = "ed05_d_publicado_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado_dia"])){ 
         $sql  .= $virgula." ed05_d_publicado = null ";
         $virgula = ",";
         if(trim($this->ed05_d_publicado) == null ){ 
           $this->erro_sql = " Campo Publicação nao Informado.";
           $this->erro_campo = "ed05_d_publicado_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed05_t_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_t_texto"])){ 
       $sql  .= $virgula." ed05_t_texto = '$this->ed05_t_texto' ";
       $virgula = ",";
     }
     if(trim($this->ed05_i_aparecehistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_aparecehistorico"])){ 
       $sql  .= $virgula." ed05_i_aparecehistorico = $this->ed05_i_aparecehistorico ";
       $virgula = ",";
       if(trim($this->ed05_i_aparecehistorico) == null ){ 
         $this->erro_sql = " Campo Aparece no Histórico nao Informado.";
         $this->erro_campo = "ed05_i_aparecehistorico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed05_i_codigo!=null){
       $sql .= " ed05_i_codigo = $this->ed05_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed05_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008212,'$this->ed05_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"]) || $this->ed05_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008212,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_codigo'))."','$this->ed05_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_numero"]) || $this->ed05_c_numero != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008215,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_c_numero'))."','$this->ed05_c_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_finalidade"]) || $this->ed05_c_finalidade != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008217,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_c_finalidade'))."','$this->ed05_c_finalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_tipoato"]) || $this->ed05_i_tipoato != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008213,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_tipoato'))."','$this->ed05_i_tipoato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_competencia"]) || $this->ed05_c_competencia != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008214,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_c_competencia'))."','$this->ed05_c_competencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_ano"]) || $this->ed05_i_ano != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008216,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_ano'))."','$this->ed05_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_orgao"]) || $this->ed05_c_orgao != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008218,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_c_orgao'))."','$this->ed05_c_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_vigora"]) || $this->ed05_d_vigora != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008219,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_d_vigora'))."','$this->ed05_d_vigora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_aprovado"]) || $this->ed05_d_aprovado != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008220,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_d_aprovado'))."','$this->ed05_d_aprovado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_d_publicado"]) || $this->ed05_d_publicado != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008221,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_d_publicado'))."','$this->ed05_d_publicado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_t_texto"]) || $this->ed05_t_texto != "")
           $resac = db_query("insert into db_acount values($acount,1010033,1008222,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_t_texto'))."','$this->ed05_t_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_aparecehistorico"]) || $this->ed05_i_aparecehistorico != "")
           $resac = db_query("insert into db_acount values($acount,1010033,17819,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_aparecehistorico'))."','$this->ed05_i_aparecehistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ato Legal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ato Legal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed05_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed05_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008212,'$ed05_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010033,1008212,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008215,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008217,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_c_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008213,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_tipoato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008214,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_c_competencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008216,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008218,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_c_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008219,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_d_vigora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008220,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_d_aprovado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008221,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_d_publicado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,1008222,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_t_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010033,17819,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_aparecehistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atolegal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed05_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed05_i_codigo = $ed05_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ato Legal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed05_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ato Legal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed05_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed05_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atolegal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atolegal ";
     $sql .= "      inner join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato";
     $sql .= "      inner join atoescola on atoescola.ed19_i_ato = atolegal.ed05_i_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed05_i_codigo!=null ){
         $sql2 .= " where atolegal.ed05_i_codigo = $ed05_i_codigo "; 
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
   function sql_query_file ( $ed05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atolegal ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed05_i_codigo!=null ){
         $sql2 .= " where atolegal.ed05_i_codigo = $ed05_i_codigo "; 
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
   function sql_query_cursos($ed05_i_codigo = null, $sCampos = "*", $sOrdem = null, $sDBWhere = "") {

     $sSql = "select ";
     
     if ($sCampos != "*" ) {

       $sCamposSQL  = split("#", $sCampos);
       $sVirgula    = "";

       for ($i = 0; $i < sizeof($sCamposSQL); $i++) {

         $sSql    .= $sVirgula.$sCamposSQL[$i];
         $sVirgula = ",";

       }

     } else {
       $sSql .= $sCampos;
     }

     $sSql .= " from atolegal ";
     $sSql .= "      inner join baseato      on baseato.ed278_i_atolegal     = atolegal.ed05_i_codigo";
     $sSql .= "      inner join baseatoserie on baseatoserie.ed279_i_baseato = baseato.ed278_i_codigo";
     $sSql .= "      inner join serie        on serie.ed11_i_codigo          = baseatoserie.ed279_i_serie";
     $sSql .= "      inner join ensino       on ensino.ed10_i_codigo         = serie.ed11_i_ensino";
     $sSql .= "      inner join cursoedu     on cursoedu.ed29_i_ensino       = ensino.ed10_i_codigo";
     $sSql .= "      inner join base         on base.ed31_i_curso            = cursoedu.ed29_i_codigo";
     $sSql .= "      inner join escolabase   on escolabase.ed77_i_base       = base.ed31_i_codigo";
     $sSql .= "                             and escolabase.ed77_i_codigo     = baseato.ed278_i_escolabase";
     $sSql .= "      inner join escola       on escola.ed18_i_codigo         = escolabase.ed77_i_escola";
     $sSql2 = "";
     
     if ($sDBWhere == "") {

       if ($ed05_i_codigo != null) {
         $sql2 .= " where atolegal.ed05_i_codigo = $ed05_i_codigo "; 
       } 

     } elseif ($sDBWhere != "") {
       $sSql2 = " where $sDBWhere";
     }

     $sSql .= $sSql2;
     
     if ($sOrdem != null) {

       $sSql       .= " order by ";
       $sCamposSQL  = split("#", $sOrdem);
       $sVirgula    = "";

       for ($i = 0; $i < sizeof($sCamposSQL); $i++) {

         $sSql    .= $sVirgula.$sCamposSQL[$i];
         $sVirgula = ",";

       }

     }

     return $sSql;
  
   }
}
?>