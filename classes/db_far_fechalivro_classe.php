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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_fechalivro
class cl_far_fechalivro { 
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
   var $fa26_i_codigo = 0; 
   var $fa26_o_arquivo = 0; 
   var $fa26_i_livro = 0; 
   var $fa26_i_login = 0; 
   var $fa26_d_dataini_dia = null; 
   var $fa26_d_dataini_mes = null; 
   var $fa26_d_dataini_ano = null; 
   var $fa26_d_dataini = null; 
   var $fa26_d_datafim_dia = null; 
   var $fa26_d_datafim_mes = null; 
   var $fa26_d_datafim_ano = null; 
   var $fa26_d_datafim = null; 
   var $fa26_d_data_dia = null; 
   var $fa26_d_data_mes = null; 
   var $fa26_d_data_ano = null; 
   var $fa26_d_data = null; 
   var $fa26_c_hora = null; 
   var $fa26_c_nomearq = null; 
   var $fa26_i_numpag = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa26_i_codigo = int4 = Código 
                 fa26_o_arquivo = oid = Arquivo 
                 fa26_i_livro = int4 = Livro 
                 fa26_i_login = int4 = Login 
                 fa26_d_dataini = date = Data Inicial 
                 fa26_d_datafim = date = Data Final 
                 fa26_d_data = date = Data 
                 fa26_c_hora = char(10) = Hora 
                 fa26_c_nomearq = char(100) = Nome do arquivo 
                 fa26_i_numpag = int4 = Número Paginas 
                 ";
   //funcao construtor da classe 
   function cl_far_fechalivro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_fechalivro"); 
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
       $this->fa26_i_codigo = ($this->fa26_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_i_codigo"]:$this->fa26_i_codigo);
       $this->fa26_o_arquivo = ($this->fa26_o_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_o_arquivo"]:$this->fa26_o_arquivo);
       $this->fa26_i_livro = ($this->fa26_i_livro == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_i_livro"]:$this->fa26_i_livro);
       $this->fa26_i_login = ($this->fa26_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_i_login"]:$this->fa26_i_login);
       if($this->fa26_d_dataini == ""){
         $this->fa26_d_dataini_dia = ($this->fa26_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini_dia"]:$this->fa26_d_dataini_dia);
         $this->fa26_d_dataini_mes = ($this->fa26_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini_mes"]:$this->fa26_d_dataini_mes);
         $this->fa26_d_dataini_ano = ($this->fa26_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini_ano"]:$this->fa26_d_dataini_ano);
         if($this->fa26_d_dataini_dia != ""){
            $this->fa26_d_dataini = $this->fa26_d_dataini_ano."-".$this->fa26_d_dataini_mes."-".$this->fa26_d_dataini_dia;
         }
       }
       if($this->fa26_d_datafim == ""){
         $this->fa26_d_datafim_dia = ($this->fa26_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim_dia"]:$this->fa26_d_datafim_dia);
         $this->fa26_d_datafim_mes = ($this->fa26_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim_mes"]:$this->fa26_d_datafim_mes);
         $this->fa26_d_datafim_ano = ($this->fa26_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim_ano"]:$this->fa26_d_datafim_ano);
         if($this->fa26_d_datafim_dia != ""){
            $this->fa26_d_datafim = $this->fa26_d_datafim_ano."-".$this->fa26_d_datafim_mes."-".$this->fa26_d_datafim_dia;
         }
       }
       if($this->fa26_d_data == ""){
         $this->fa26_d_data_dia = ($this->fa26_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_data_dia"]:$this->fa26_d_data_dia);
         $this->fa26_d_data_mes = ($this->fa26_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_data_mes"]:$this->fa26_d_data_mes);
         $this->fa26_d_data_ano = ($this->fa26_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_d_data_ano"]:$this->fa26_d_data_ano);
         if($this->fa26_d_data_dia != ""){
            $this->fa26_d_data = $this->fa26_d_data_ano."-".$this->fa26_d_data_mes."-".$this->fa26_d_data_dia;
         }
       }
       $this->fa26_c_hora = ($this->fa26_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_c_hora"]:$this->fa26_c_hora);
       $this->fa26_c_nomearq = ($this->fa26_c_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_c_nomearq"]:$this->fa26_c_nomearq);
       $this->fa26_i_numpag = ($this->fa26_i_numpag == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_i_numpag"]:$this->fa26_i_numpag);
     }else{
       $this->fa26_i_codigo = ($this->fa26_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa26_i_codigo"]:$this->fa26_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa26_i_codigo){ 
      $this->atualizacampos();
     if($this->fa26_o_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "fa26_o_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_i_livro == null ){ 
       $this->erro_sql = " Campo Livro nao Informado.";
       $this->erro_campo = "fa26_i_livro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "fa26_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_d_dataini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "fa26_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_d_datafim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "fa26_d_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "fa26_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "fa26_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_c_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "fa26_c_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa26_i_numpag == null ){ 
       $this->erro_sql = " Campo Número Paginas nao Informado.";
       $this->erro_campo = "fa26_i_numpag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa26_i_codigo == "" || $fa26_i_codigo == null ){
       $result = db_query("select nextval('far_fechalivro_fa26_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_fechalivro_fa26_codigo_seq do campo: fa26_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa26_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_fechalivro_fa26_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa26_i_codigo)){
         $this->erro_sql = " Campo fa26_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa26_i_codigo = $fa26_i_codigo; 
       }
     }
     if(($this->fa26_i_codigo == null) || ($this->fa26_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa26_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_fechalivro(
                                       fa26_i_codigo 
                                      ,fa26_o_arquivo 
                                      ,fa26_i_livro 
                                      ,fa26_i_login 
                                      ,fa26_d_dataini 
                                      ,fa26_d_datafim 
                                      ,fa26_d_data 
                                      ,fa26_c_hora 
                                      ,fa26_c_nomearq 
                                      ,fa26_i_numpag 
                       )
                values (
                                $this->fa26_i_codigo 
                               ,$this->fa26_o_arquivo 
                               ,$this->fa26_i_livro 
                               ,$this->fa26_i_login 
                               ,".($this->fa26_d_dataini == "null" || $this->fa26_d_dataini == ""?"null":"'".$this->fa26_d_dataini."'")." 
                               ,".($this->fa26_d_datafim == "null" || $this->fa26_d_datafim == ""?"null":"'".$this->fa26_d_datafim."'")." 
                               ,".($this->fa26_d_data == "null" || $this->fa26_d_data == ""?"null":"'".$this->fa26_d_data."'")." 
                               ,'$this->fa26_c_hora' 
                               ,'$this->fa26_c_nomearq' 
                               ,$this->fa26_i_numpag 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_fechalivro ($this->fa26_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_fechalivro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_fechalivro ($this->fa26_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa26_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa26_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14137,'$this->fa26_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2486,14137,'','".AddSlashes(pg_result($resaco,0,'fa26_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14138,'','".AddSlashes(pg_result($resaco,0,'fa26_o_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14139,'','".AddSlashes(pg_result($resaco,0,'fa26_i_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14140,'','".AddSlashes(pg_result($resaco,0,'fa26_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14141,'','".AddSlashes(pg_result($resaco,0,'fa26_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14142,'','".AddSlashes(pg_result($resaco,0,'fa26_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14144,'','".AddSlashes(pg_result($resaco,0,'fa26_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14143,'','".AddSlashes(pg_result($resaco,0,'fa26_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14268,'','".AddSlashes(pg_result($resaco,0,'fa26_c_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2486,14286,'','".AddSlashes(pg_result($resaco,0,'fa26_i_numpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa26_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_fechalivro set ";
     $virgula = "";
     if(trim($this->fa26_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_codigo"])){ 
       $sql  .= $virgula." fa26_i_codigo = $this->fa26_i_codigo ";
       $virgula = ",";
       if(trim($this->fa26_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa26_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa26_o_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_o_arquivo"])){ 
       $sql  .= $virgula." fa26_o_arquivo = $this->fa26_o_arquivo ";
       $virgula = ",";
       if(trim($this->fa26_o_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "fa26_o_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa26_i_livro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_livro"])){ 
       $sql  .= $virgula." fa26_i_livro = $this->fa26_i_livro ";
       $virgula = ",";
       if(trim($this->fa26_i_livro) == null ){ 
         $this->erro_sql = " Campo Livro nao Informado.";
         $this->erro_campo = "fa26_i_livro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa26_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_login"])){ 
       $sql  .= $virgula." fa26_i_login = $this->fa26_i_login ";
       $virgula = ",";
       if(trim($this->fa26_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "fa26_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa26_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini_dia"] !="") ){ 
       $sql  .= $virgula." fa26_d_dataini = '$this->fa26_d_dataini' ";
       $virgula = ",";
       if(trim($this->fa26_d_dataini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "fa26_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini_dia"])){ 
         $sql  .= $virgula." fa26_d_dataini = null ";
         $virgula = ",";
         if(trim($this->fa26_d_dataini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "fa26_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa26_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." fa26_d_datafim = '$this->fa26_d_datafim' ";
       $virgula = ",";
       if(trim($this->fa26_d_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "fa26_d_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim_dia"])){ 
         $sql  .= $virgula." fa26_d_datafim = null ";
         $virgula = ",";
         if(trim($this->fa26_d_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "fa26_d_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa26_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa26_d_data_dia"] !="") ){ 
       $sql  .= $virgula." fa26_d_data = '$this->fa26_d_data' ";
       $virgula = ",";
       if(trim($this->fa26_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "fa26_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_data_dia"])){ 
         $sql  .= $virgula." fa26_d_data = null ";
         $virgula = ",";
         if(trim($this->fa26_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "fa26_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa26_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_c_hora"])){ 
       $sql  .= $virgula." fa26_c_hora = '$this->fa26_c_hora' ";
       $virgula = ",";
       if(trim($this->fa26_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "fa26_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa26_c_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_c_nomearq"])){ 
       $sql  .= $virgula." fa26_c_nomearq = '$this->fa26_c_nomearq' ";
       $virgula = ",";
       if(trim($this->fa26_c_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "fa26_c_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa26_i_numpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_numpag"])){ 
       $sql  .= $virgula." fa26_i_numpag = $this->fa26_i_numpag ";
       $virgula = ",";
       if(trim($this->fa26_i_numpag) == null ){ 
         $this->erro_sql = " Campo Número Paginas nao Informado.";
         $this->erro_campo = "fa26_i_numpag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa26_i_codigo!=null){
       $sql .= " fa26_i_codigo = $this->fa26_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa26_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14137,'$this->fa26_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_codigo"]) || $this->fa26_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2486,14137,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_i_codigo'))."','$this->fa26_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_o_arquivo"]) || $this->fa26_o_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,2486,14138,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_o_arquivo'))."','$this->fa26_o_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_livro"]) || $this->fa26_i_livro != "")
           $resac = db_query("insert into db_acount values($acount,2486,14139,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_i_livro'))."','$this->fa26_i_livro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_login"]) || $this->fa26_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2486,14140,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_i_login'))."','$this->fa26_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_dataini"]) || $this->fa26_d_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2486,14141,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_d_dataini'))."','$this->fa26_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_datafim"]) || $this->fa26_d_datafim != "")
           $resac = db_query("insert into db_acount values($acount,2486,14142,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_d_datafim'))."','$this->fa26_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_d_data"]) || $this->fa26_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2486,14144,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_d_data'))."','$this->fa26_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_c_hora"]) || $this->fa26_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2486,14143,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_c_hora'))."','$this->fa26_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_c_nomearq"]) || $this->fa26_c_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,2486,14268,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_c_nomearq'))."','$this->fa26_c_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa26_i_numpag"]) || $this->fa26_i_numpag != "")
           $resac = db_query("insert into db_acount values($acount,2486,14286,'".AddSlashes(pg_result($resaco,$conresaco,'fa26_i_numpag'))."','$this->fa26_i_numpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_fechalivro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa26_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_fechalivro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa26_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa26_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14137,'$fa26_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2486,14137,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14138,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_o_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14139,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_i_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14140,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14141,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14142,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14144,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14143,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14268,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_c_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2486,14286,'','".AddSlashes(pg_result($resaco,$iresaco,'fa26_i_numpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_fechalivro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa26_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa26_i_codigo = $fa26_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_fechalivro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa26_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_fechalivro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa26_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_fechalivro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_fechalivro ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_fechalivro.fa26_i_login";
     $sql .= "      inner join far_modelolivro  on  far_modelolivro.fa16_i_codigo = far_fechalivro.fa26_i_livro";
     $sql2 = "";
     if($dbwhere==""){
       if($fa26_i_codigo!=null ){
         $sql2 .= " where far_fechalivro.fa26_i_codigo = $fa26_i_codigo "; 
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
   function sql_query_file ( $fa26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_fechalivro ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa26_i_codigo!=null ){
         $sql2 .= " where far_fechalivro.fa26_i_codigo = $fa26_i_codigo "; 
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