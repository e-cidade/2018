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

//MODULO: issqn
//CLASSE DA ENTIDADE viabilidade
class cl_viabilidade { 
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
   var $q29_codigo = 0; 
   var $q29_data_dia = null; 
   var $q29_data_mes = null; 
   var $q29_data_ano = null; 
   var $q29_data = null; 
   var $q29_numcgm = 0; 
   var $q29_lograd = 0; 
   var $q29_numero = 0; 
   var $q29_complem = null; 
   var $q29_bairro = 0; 
   var $q29_ativ = 0; 
   var $q29_escrito = 0; 
   var $q29_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q29_codigo = int4 = codigo da viabilidade 
                 q29_data = date = data da viabilidade 
                 q29_numcgm = int4 = numero do cgm 
                 q29_lograd = int4 = logradouro 
                 q29_numero = int4 = numero 
                 q29_complem = varchar(20) = complemento 
                 q29_bairro = int4 = bairro 
                 q29_ativ = int4 = atividade 
                 q29_escrito = int4 = escritorio contabil 
                 q29_tipo = char(1) = tipo de solicitacao 
                 ";
   //funcao construtor da classe 
   function cl_viabilidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("viabilidade"); 
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
       $this->q29_codigo = ($this->q29_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_codigo"]:$this->q29_codigo);
       if($this->q29_data == ""){
         $this->q29_data_dia = ($this->q29_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_data_dia"]:$this->q29_data_dia);
         $this->q29_data_mes = ($this->q29_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_data_mes"]:$this->q29_data_mes);
         $this->q29_data_ano = ($this->q29_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_data_ano"]:$this->q29_data_ano);
         if($this->q29_data_dia != ""){
            $this->q29_data = $this->q29_data_ano."-".$this->q29_data_mes."-".$this->q29_data_dia;
         }
       }
       $this->q29_numcgm = ($this->q29_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_numcgm"]:$this->q29_numcgm);
       $this->q29_lograd = ($this->q29_lograd == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_lograd"]:$this->q29_lograd);
       $this->q29_numero = ($this->q29_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_numero"]:$this->q29_numero);
       $this->q29_complem = ($this->q29_complem == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_complem"]:$this->q29_complem);
       $this->q29_bairro = ($this->q29_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_bairro"]:$this->q29_bairro);
       $this->q29_ativ = ($this->q29_ativ == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_ativ"]:$this->q29_ativ);
       $this->q29_escrito = ($this->q29_escrito == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_escrito"]:$this->q29_escrito);
       $this->q29_tipo = ($this->q29_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_tipo"]:$this->q29_tipo);
     }else{
       $this->q29_codigo = ($this->q29_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q29_codigo"]:$this->q29_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q29_codigo){ 
      $this->atualizacampos();
     if($this->q29_data == null ){ 
       $this->erro_sql = " Campo data da viabilidade nao Informado.";
       $this->erro_campo = "q29_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q29_numcgm == null ){ 
       $this->erro_sql = " Campo numero do cgm nao Informado.";
       $this->erro_campo = "q29_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q29_lograd == null ){ 
       $this->erro_sql = " Campo logradouro nao Informado.";
       $this->erro_campo = "q29_lograd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q29_numero == null ){ 
       $this->erro_sql = " Campo numero nao Informado.";
       $this->erro_campo = "q29_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q29_bairro == null ){ 
       $this->erro_sql = " Campo bairro nao Informado.";
       $this->erro_campo = "q29_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q29_ativ == null ){ 
       $this->erro_sql = " Campo atividade nao Informado.";
       $this->erro_campo = "q29_ativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q29_escrito == null ){ 
       $this->q29_escrito = "0";
     }
     if($this->q29_tipo == null ){ 
       $this->erro_sql = " Campo tipo de solicitacao nao Informado.";
       $this->erro_campo = "q29_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q29_codigo == "" || $q29_codigo == null ){
       $result = db_query("select nextval('viabilidade_q29_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: viabilidade_q29_codigo_seq do campo: q29_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q29_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from viabilidade_q29_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q29_codigo)){
         $this->erro_sql = " Campo q29_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q29_codigo = $q29_codigo; 
       }
     }
     if(($this->q29_codigo == null) || ($this->q29_codigo == "") ){ 
       $this->erro_sql = " Campo q29_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into viabilidade(
                                       q29_codigo 
                                      ,q29_data 
                                      ,q29_numcgm 
                                      ,q29_lograd 
                                      ,q29_numero 
                                      ,q29_complem 
                                      ,q29_bairro 
                                      ,q29_ativ 
                                      ,q29_escrito 
                                      ,q29_tipo 
                       )
                values (
                                $this->q29_codigo 
                               ,".($this->q29_data == "null" || $this->q29_data == ""?"null":"'".$this->q29_data."'")." 
                               ,$this->q29_numcgm 
                               ,$this->q29_lograd 
                               ,$this->q29_numero 
                               ,'$this->q29_complem' 
                               ,$this->q29_bairro 
                               ,$this->q29_ativ 
                               ,$this->q29_escrito 
                               ,'$this->q29_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "viabilidade ($this->q29_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "viabilidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "viabilidade ($this->q29_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q29_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q29_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2390,'$this->q29_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,388,2390,'','".AddSlashes(pg_result($resaco,0,'q29_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2391,'','".AddSlashes(pg_result($resaco,0,'q29_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2392,'','".AddSlashes(pg_result($resaco,0,'q29_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2393,'','".AddSlashes(pg_result($resaco,0,'q29_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2394,'','".AddSlashes(pg_result($resaco,0,'q29_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2395,'','".AddSlashes(pg_result($resaco,0,'q29_complem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2396,'','".AddSlashes(pg_result($resaco,0,'q29_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2397,'','".AddSlashes(pg_result($resaco,0,'q29_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2398,'','".AddSlashes(pg_result($resaco,0,'q29_escrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,388,2399,'','".AddSlashes(pg_result($resaco,0,'q29_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q29_codigo=null) { 
      $this->atualizacampos();
     $sql = " update viabilidade set ";
     $virgula = "";
     if(trim($this->q29_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_codigo"])){ 
       $sql  .= $virgula." q29_codigo = $this->q29_codigo ";
       $virgula = ",";
       if(trim($this->q29_codigo) == null ){ 
         $this->erro_sql = " Campo codigo da viabilidade nao Informado.";
         $this->erro_campo = "q29_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q29_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q29_data_dia"] !="") ){ 
       $sql  .= $virgula." q29_data = '$this->q29_data' ";
       $virgula = ",";
       if(trim($this->q29_data) == null ){ 
         $this->erro_sql = " Campo data da viabilidade nao Informado.";
         $this->erro_campo = "q29_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q29_data_dia"])){ 
         $sql  .= $virgula." q29_data = null ";
         $virgula = ",";
         if(trim($this->q29_data) == null ){ 
           $this->erro_sql = " Campo data da viabilidade nao Informado.";
           $this->erro_campo = "q29_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q29_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_numcgm"])){ 
       $sql  .= $virgula." q29_numcgm = $this->q29_numcgm ";
       $virgula = ",";
       if(trim($this->q29_numcgm) == null ){ 
         $this->erro_sql = " Campo numero do cgm nao Informado.";
         $this->erro_campo = "q29_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q29_lograd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_lograd"])){ 
       $sql  .= $virgula." q29_lograd = $this->q29_lograd ";
       $virgula = ",";
       if(trim($this->q29_lograd) == null ){ 
         $this->erro_sql = " Campo logradouro nao Informado.";
         $this->erro_campo = "q29_lograd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q29_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_numero"])){ 
       $sql  .= $virgula." q29_numero = $this->q29_numero ";
       $virgula = ",";
       if(trim($this->q29_numero) == null ){ 
         $this->erro_sql = " Campo numero nao Informado.";
         $this->erro_campo = "q29_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q29_complem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_complem"])){ 
       $sql  .= $virgula." q29_complem = '$this->q29_complem' ";
       $virgula = ",";
     }
     if(trim($this->q29_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_bairro"])){ 
       $sql  .= $virgula." q29_bairro = $this->q29_bairro ";
       $virgula = ",";
       if(trim($this->q29_bairro) == null ){ 
         $this->erro_sql = " Campo bairro nao Informado.";
         $this->erro_campo = "q29_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q29_ativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_ativ"])){ 
       $sql  .= $virgula." q29_ativ = $this->q29_ativ ";
       $virgula = ",";
       if(trim($this->q29_ativ) == null ){ 
         $this->erro_sql = " Campo atividade nao Informado.";
         $this->erro_campo = "q29_ativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q29_escrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_escrito"])){ 
        if(trim($this->q29_escrito)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q29_escrito"])){ 
           $this->q29_escrito = "0" ; 
        } 
       $sql  .= $virgula." q29_escrito = $this->q29_escrito ";
       $virgula = ",";
     }
     if(trim($this->q29_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q29_tipo"])){ 
       $sql  .= $virgula." q29_tipo = '$this->q29_tipo' ";
       $virgula = ",";
       if(trim($this->q29_tipo) == null ){ 
         $this->erro_sql = " Campo tipo de solicitacao nao Informado.";
         $this->erro_campo = "q29_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q29_codigo!=null){
       $sql .= " q29_codigo = $this->q29_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q29_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2390,'$this->q29_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_codigo"]))
           $resac = db_query("insert into db_acount values($acount,388,2390,'".AddSlashes(pg_result($resaco,$conresaco,'q29_codigo'))."','$this->q29_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_data"]))
           $resac = db_query("insert into db_acount values($acount,388,2391,'".AddSlashes(pg_result($resaco,$conresaco,'q29_data'))."','$this->q29_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,388,2392,'".AddSlashes(pg_result($resaco,$conresaco,'q29_numcgm'))."','$this->q29_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_lograd"]))
           $resac = db_query("insert into db_acount values($acount,388,2393,'".AddSlashes(pg_result($resaco,$conresaco,'q29_lograd'))."','$this->q29_lograd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_numero"]))
           $resac = db_query("insert into db_acount values($acount,388,2394,'".AddSlashes(pg_result($resaco,$conresaco,'q29_numero'))."','$this->q29_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_complem"]))
           $resac = db_query("insert into db_acount values($acount,388,2395,'".AddSlashes(pg_result($resaco,$conresaco,'q29_complem'))."','$this->q29_complem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_bairro"]))
           $resac = db_query("insert into db_acount values($acount,388,2396,'".AddSlashes(pg_result($resaco,$conresaco,'q29_bairro'))."','$this->q29_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_ativ"]))
           $resac = db_query("insert into db_acount values($acount,388,2397,'".AddSlashes(pg_result($resaco,$conresaco,'q29_ativ'))."','$this->q29_ativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_escrito"]))
           $resac = db_query("insert into db_acount values($acount,388,2398,'".AddSlashes(pg_result($resaco,$conresaco,'q29_escrito'))."','$this->q29_escrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q29_tipo"]))
           $resac = db_query("insert into db_acount values($acount,388,2399,'".AddSlashes(pg_result($resaco,$conresaco,'q29_tipo'))."','$this->q29_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "viabilidade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q29_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "viabilidade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q29_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q29_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q29_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q29_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2390,'$q29_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,388,2390,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2391,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2392,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2393,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2394,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2395,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_complem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2396,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2397,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2398,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_escrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,388,2399,'','".AddSlashes(pg_result($resaco,$iresaco,'q29_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from viabilidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q29_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q29_codigo = $q29_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "viabilidade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q29_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "viabilidade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q29_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q29_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:viabilidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q29_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from viabilidade ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = viabilidade.q29_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = viabilidade.q29_lograd";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = viabilidade.q29_numcgm";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = viabilidade.q29_ativ";
     $sql .= "      inner join cadescrito  on  cadescrito.q86_numcgm = viabilidade.q29_escrito";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = cadescrito.q86_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q29_codigo!=null ){
         $sql2 .= " where viabilidade.q29_codigo = $q29_codigo "; 
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
   function sql_query_file ( $q29_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from viabilidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($q29_codigo!=null ){
         $sql2 .= " where viabilidade.q29_codigo = $q29_codigo "; 
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