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

//MODULO: dividaativa
//CLASSE DA ENTIDADE divimporta
class cl_divimporta { 
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
   var $v02_divimporta = 0; 
   var $v02_usuario = 0; 
   var $v02_data_dia = null; 
   var $v02_data_mes = null; 
   var $v02_data_ano = null; 
   var $v02_data = null; 
   var $v02_hora = null; 
   var $v02_horafim = null; 
   var $v02_datafim_dia = null; 
   var $v02_datafim_mes = null; 
   var $v02_datafim_ano = null; 
   var $v02_datafim = null; 
   var $v02_tipo = 0; 
   var $v02_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v02_divimporta = int4 = Código da importação 
                 v02_usuario = int4 = Cod. Usuário 
                 v02_data = date = Data da importação 
                 v02_hora = varchar(10) = Hora da importação 
                 v02_horafim = varchar(10) = Hora final 
                 v02_datafim = date = Data final 
                 v02_tipo = int4 = Tipo 
                 v02_instit = int4 = Cod. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_divimporta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("divimporta"); 
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
       $this->v02_divimporta = ($this->v02_divimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_divimporta"]:$this->v02_divimporta);
       $this->v02_usuario = ($this->v02_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_usuario"]:$this->v02_usuario);
       if($this->v02_data == ""){
         $this->v02_data_dia = ($this->v02_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_data_dia"]:$this->v02_data_dia);
         $this->v02_data_mes = ($this->v02_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_data_mes"]:$this->v02_data_mes);
         $this->v02_data_ano = ($this->v02_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_data_ano"]:$this->v02_data_ano);
         if($this->v02_data_dia != ""){
            $this->v02_data = $this->v02_data_ano."-".$this->v02_data_mes."-".$this->v02_data_dia;
         }
       }
       $this->v02_hora = ($this->v02_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_hora"]:$this->v02_hora);
       $this->v02_horafim = ($this->v02_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_horafim"]:$this->v02_horafim);
       if($this->v02_datafim == ""){
         $this->v02_datafim_dia = ($this->v02_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_datafim_dia"]:$this->v02_datafim_dia);
         $this->v02_datafim_mes = ($this->v02_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_datafim_mes"]:$this->v02_datafim_mes);
         $this->v02_datafim_ano = ($this->v02_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_datafim_ano"]:$this->v02_datafim_ano);
         if($this->v02_datafim_dia != ""){
            $this->v02_datafim = $this->v02_datafim_ano."-".$this->v02_datafim_mes."-".$this->v02_datafim_dia;
         }
       }
       $this->v02_tipo = ($this->v02_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_tipo"]:$this->v02_tipo);
       $this->v02_instit = ($this->v02_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_instit"]:$this->v02_instit);
     }else{
       $this->v02_divimporta = ($this->v02_divimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["v02_divimporta"]:$this->v02_divimporta);
     }
   }
   // funcao para inclusao
   function incluir ($v02_divimporta){ 
      $this->atualizacampos();
     if($this->v02_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "v02_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v02_data == null ){ 
       $this->erro_sql = " Campo Data da importação nao Informado.";
       $this->erro_campo = "v02_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v02_hora == null ){ 
       $this->erro_sql = " Campo Hora da importação nao Informado.";
       $this->erro_campo = "v02_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v02_horafim == null ){ 
       $this->erro_sql = " Campo Hora final nao Informado.";
       $this->erro_campo = "v02_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v02_datafim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "v02_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v02_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "v02_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v02_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "v02_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v02_divimporta == "" || $v02_divimporta == null ){
       $result = db_query("select nextval('divimporta_v02_divimporta_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: divimporta_v02_divimporta_seq do campo: v02_divimporta"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v02_divimporta = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from divimporta_v02_divimporta_seq");
       if(($result != false) && (pg_result($result,0,0) < $v02_divimporta)){
         $this->erro_sql = " Campo v02_divimporta maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v02_divimporta = $v02_divimporta; 
       }
     }
     if(($this->v02_divimporta == null) || ($this->v02_divimporta == "") ){ 
       $this->erro_sql = " Campo v02_divimporta nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into divimporta(
                                       v02_divimporta 
                                      ,v02_usuario 
                                      ,v02_data 
                                      ,v02_hora 
                                      ,v02_horafim 
                                      ,v02_datafim 
                                      ,v02_tipo 
                                      ,v02_instit 
                       )
                values (
                                $this->v02_divimporta 
                               ,$this->v02_usuario 
                               ,".($this->v02_data == "null" || $this->v02_data == ""?"null":"'".$this->v02_data."'")." 
                               ,'$this->v02_hora' 
                               ,'$this->v02_horafim' 
                               ,".($this->v02_datafim == "null" || $this->v02_datafim == ""?"null":"'".$this->v02_datafim."'")." 
                               ,$this->v02_tipo 
                               ,$this->v02_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lote da importação ($this->v02_divimporta) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lote da importação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lote da importação ($this->v02_divimporta) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v02_divimporta;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v02_divimporta));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8237,'$this->v02_divimporta','I')");
       $resac = db_query("insert into db_acount values($acount,1387,8237,'','".AddSlashes(pg_result($resaco,0,'v02_divimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,8240,'','".AddSlashes(pg_result($resaco,0,'v02_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,8238,'','".AddSlashes(pg_result($resaco,0,'v02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,8239,'','".AddSlashes(pg_result($resaco,0,'v02_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,9862,'','".AddSlashes(pg_result($resaco,0,'v02_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,9861,'','".AddSlashes(pg_result($resaco,0,'v02_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,9860,'','".AddSlashes(pg_result($resaco,0,'v02_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1387,10574,'','".AddSlashes(pg_result($resaco,0,'v02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v02_divimporta=null) { 
      $this->atualizacampos();
     $sql = " update divimporta set ";
     $virgula = "";
     if(trim($this->v02_divimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_divimporta"])){ 
       $sql  .= $virgula." v02_divimporta = $this->v02_divimporta ";
       $virgula = ",";
       if(trim($this->v02_divimporta) == null ){ 
         $this->erro_sql = " Campo Código da importação nao Informado.";
         $this->erro_campo = "v02_divimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v02_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_usuario"])){ 
       $sql  .= $virgula." v02_usuario = $this->v02_usuario ";
       $virgula = ",";
       if(trim($this->v02_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "v02_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v02_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v02_data_dia"] !="") ){ 
       $sql  .= $virgula." v02_data = '$this->v02_data' ";
       $virgula = ",";
       if(trim($this->v02_data) == null ){ 
         $this->erro_sql = " Campo Data da importação nao Informado.";
         $this->erro_campo = "v02_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v02_data_dia"])){ 
         $sql  .= $virgula." v02_data = null ";
         $virgula = ",";
         if(trim($this->v02_data) == null ){ 
           $this->erro_sql = " Campo Data da importação nao Informado.";
           $this->erro_campo = "v02_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v02_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_hora"])){ 
       $sql  .= $virgula." v02_hora = '$this->v02_hora' ";
       $virgula = ",";
       if(trim($this->v02_hora) == null ){ 
         $this->erro_sql = " Campo Hora da importação nao Informado.";
         $this->erro_campo = "v02_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v02_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_horafim"])){ 
       $sql  .= $virgula." v02_horafim = '$this->v02_horafim' ";
       $virgula = ",";
       if(trim($this->v02_horafim) == null ){ 
         $this->erro_sql = " Campo Hora final nao Informado.";
         $this->erro_campo = "v02_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v02_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v02_datafim_dia"] !="") ){ 
       $sql  .= $virgula." v02_datafim = '$this->v02_datafim' ";
       $virgula = ",";
       if(trim($this->v02_datafim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "v02_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v02_datafim_dia"])){ 
         $sql  .= $virgula." v02_datafim = null ";
         $virgula = ",";
         if(trim($this->v02_datafim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "v02_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v02_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_tipo"])){ 
       $sql  .= $virgula." v02_tipo = $this->v02_tipo ";
       $virgula = ",";
       if(trim($this->v02_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "v02_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v02_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v02_instit"])){ 
       $sql  .= $virgula." v02_instit = $this->v02_instit ";
       $virgula = ",";
       if(trim($this->v02_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "v02_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v02_divimporta!=null){
       $sql .= " v02_divimporta = $this->v02_divimporta";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v02_divimporta));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8237,'$this->v02_divimporta','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_divimporta"]))
           $resac = db_query("insert into db_acount values($acount,1387,8237,'".AddSlashes(pg_result($resaco,$conresaco,'v02_divimporta'))."','$this->v02_divimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1387,8240,'".AddSlashes(pg_result($resaco,$conresaco,'v02_usuario'))."','$this->v02_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_data"]))
           $resac = db_query("insert into db_acount values($acount,1387,8238,'".AddSlashes(pg_result($resaco,$conresaco,'v02_data'))."','$this->v02_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_hora"]))
           $resac = db_query("insert into db_acount values($acount,1387,8239,'".AddSlashes(pg_result($resaco,$conresaco,'v02_hora'))."','$this->v02_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_horafim"]))
           $resac = db_query("insert into db_acount values($acount,1387,9862,'".AddSlashes(pg_result($resaco,$conresaco,'v02_horafim'))."','$this->v02_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_datafim"]))
           $resac = db_query("insert into db_acount values($acount,1387,9861,'".AddSlashes(pg_result($resaco,$conresaco,'v02_datafim'))."','$this->v02_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1387,9860,'".AddSlashes(pg_result($resaco,$conresaco,'v02_tipo'))."','$this->v02_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v02_instit"]))
           $resac = db_query("insert into db_acount values($acount,1387,10574,'".AddSlashes(pg_result($resaco,$conresaco,'v02_instit'))."','$this->v02_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote da importação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v02_divimporta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote da importação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v02_divimporta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v02_divimporta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v02_divimporta=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v02_divimporta));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8237,'$v02_divimporta','E')");
         $resac = db_query("insert into db_acount values($acount,1387,8237,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_divimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,8240,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,8238,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,8239,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,9862,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,9861,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,9860,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1387,10574,'','".AddSlashes(pg_result($resaco,$iresaco,'v02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from divimporta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v02_divimporta != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v02_divimporta = $v02_divimporta ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote da importação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v02_divimporta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote da importação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v02_divimporta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v02_divimporta;
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
        $this->erro_sql   = "Record Vazio na Tabela:divimporta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v02_divimporta=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from divimporta ";
     $sql .= "      inner join db_config  on  db_config.codigo = divimporta.v02_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($v02_divimporta!=null ){
         $sql2 .= " where divimporta.v02_divimporta = $v02_divimporta "; 
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
   function sql_query_file ( $v02_divimporta=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from divimporta ";
     $sql2 = "";
     if($dbwhere==""){
       if($v02_divimporta!=null ){
         $sql2 .= " where divimporta.v02_divimporta = $v02_divimporta "; 
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
   function __toString() {
     return "Object";
   }
}
?>