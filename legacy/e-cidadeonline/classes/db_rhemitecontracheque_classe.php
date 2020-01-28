<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhemitecontracheque
class cl_rhemitecontracheque { 
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
   var $rh85_sequencial = 0; 
   var $rh85_regist = 0; 
   var $rh85_anousu = 0; 
   var $rh85_mesusu = 0; 
   var $rh85_dataemissao_dia = null; 
   var $rh85_dataemissao_mes = null; 
   var $rh85_dataemissao_ano = null; 
   var $rh85_dataemissao = null; 
   var $rh85_horaemissao = null; 
   var $rh85_sigla = null; 
   var $rh85_codautent = null; 
   var $rh85_ip = null; 
   var $rh85_externo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh85_sequencial = int4 = rh85_sequencial 
                 rh85_regist = int8 = rh85_regist 
                 rh85_anousu = int4 = rh85_anousu 
                 rh85_mesusu = int4 = rh85_mesusu 
                 rh85_dataemissao = date = rh85_dataemissao 
                 rh85_horaemissao = char(5) = rh85_horaemissao 
                 rh85_sigla = char(3) = rh85_sigla 
                 rh85_codautent = varchar(20) = rh85_codautent 
                 rh85_ip = varchar(15) = rh85_ip 
                 rh85_externo = bool = rh85_externo 
                 ";
   //funcao construtor da classe 
   function cl_rhemitecontracheque() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhemitecontracheque"); 
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
       $this->rh85_sequencial = ($this->rh85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"]:$this->rh85_sequencial);
       $this->rh85_regist = ($this->rh85_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_regist"]:$this->rh85_regist);
       $this->rh85_anousu = ($this->rh85_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_anousu"]:$this->rh85_anousu);
       $this->rh85_mesusu = ($this->rh85_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_mesusu"]:$this->rh85_mesusu);
       if($this->rh85_dataemissao == ""){
         $this->rh85_dataemissao_dia = ($this->rh85_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"]:$this->rh85_dataemissao_dia);
         $this->rh85_dataemissao_mes = ($this->rh85_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_mes"]:$this->rh85_dataemissao_mes);
         $this->rh85_dataemissao_ano = ($this->rh85_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_ano"]:$this->rh85_dataemissao_ano);
         if($this->rh85_dataemissao_dia != ""){
            $this->rh85_dataemissao = $this->rh85_dataemissao_ano."-".$this->rh85_dataemissao_mes."-".$this->rh85_dataemissao_dia;
         }
       }
       $this->rh85_horaemissao = ($this->rh85_horaemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_horaemissao"]:$this->rh85_horaemissao);
       $this->rh85_sigla = ($this->rh85_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_sigla"]:$this->rh85_sigla);
       $this->rh85_codautent = ($this->rh85_codautent == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_codautent"]:$this->rh85_codautent);
       $this->rh85_ip = ($this->rh85_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_ip"]:$this->rh85_ip);
       $this->rh85_externo = ($this->rh85_externo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh85_externo"]:$this->rh85_externo);
     }else{
       $this->rh85_sequencial = ($this->rh85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"]:$this->rh85_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh85_sequencial){ 
      $this->atualizacampos();
     if($this->rh85_regist == null ){ 
       $this->erro_sql = " Campo rh85_regist nao Informado.";
       $this->erro_campo = "rh85_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_anousu == null ){ 
       $this->erro_sql = " Campo rh85_anousu nao Informado.";
       $this->erro_campo = "rh85_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_mesusu == null ){ 
       $this->erro_sql = " Campo rh85_mesusu nao Informado.";
       $this->erro_campo = "rh85_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_dataemissao == null ){ 
       $this->erro_sql = " Campo rh85_dataemissao nao Informado.";
       $this->erro_campo = "rh85_dataemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_horaemissao == null ){ 
       $this->erro_sql = " Campo rh85_horaemissao nao Informado.";
       $this->erro_campo = "rh85_horaemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_sigla == null ){ 
       $this->erro_sql = " Campo rh85_sigla nao Informado.";
       $this->erro_campo = "rh85_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_codautent == null ){ 
       $this->erro_sql = " Campo rh85_codautent nao Informado.";
       $this->erro_campo = "rh85_codautent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_ip == null ){ 
       $this->erro_sql = " Campo rh85_ip nao Informado.";
       $this->erro_campo = "rh85_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_externo == null ){ 
       $this->erro_sql = " Campo rh85_externo nao Informado.";
       $this->erro_campo = "rh85_externo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh85_sequencial == "" || $rh85_sequencial == null ){
       $result = db_query("select nextval('rhemitecontracheque_rh85_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhemitecontracheque_rh85_sequencial_seq do campo: rh85_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh85_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhemitecontracheque_rh85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh85_sequencial)){
         $this->erro_sql = " Campo rh85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh85_sequencial = $rh85_sequencial; 
       }
     }
     if(($this->rh85_sequencial == null) || ($this->rh85_sequencial == "") ){ 
       $this->erro_sql = " Campo rh85_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhemitecontracheque(
                                       rh85_sequencial 
                                      ,rh85_regist 
                                      ,rh85_anousu 
                                      ,rh85_mesusu 
                                      ,rh85_dataemissao 
                                      ,rh85_horaemissao 
                                      ,rh85_sigla 
                                      ,rh85_codautent 
                                      ,rh85_ip 
                                      ,rh85_externo 
                       )
                values (
                                $this->rh85_sequencial 
                               ,$this->rh85_regist 
                               ,$this->rh85_anousu 
                               ,$this->rh85_mesusu 
                               ,".($this->rh85_dataemissao == "null" || $this->rh85_dataemissao == ""?"null":"'".$this->rh85_dataemissao."'")." 
                               ,'$this->rh85_horaemissao' 
                               ,'$this->rh85_sigla' 
                               ,'$this->rh85_codautent' 
                               ,'$this->rh85_ip' 
                               ,'$this->rh85_externo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhemitecontracheque ($this->rh85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhemitecontracheque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhemitecontracheque ($this->rh85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh85_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14571,'$this->rh85_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2563,14571,'','".AddSlashes(pg_result($resaco,0,'rh85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14562,'','".AddSlashes(pg_result($resaco,0,'rh85_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14563,'','".AddSlashes(pg_result($resaco,0,'rh85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14564,'','".AddSlashes(pg_result($resaco,0,'rh85_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14565,'','".AddSlashes(pg_result($resaco,0,'rh85_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14566,'','".AddSlashes(pg_result($resaco,0,'rh85_horaemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14567,'','".AddSlashes(pg_result($resaco,0,'rh85_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14568,'','".AddSlashes(pg_result($resaco,0,'rh85_codautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14569,'','".AddSlashes(pg_result($resaco,0,'rh85_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2563,14570,'','".AddSlashes(pg_result($resaco,0,'rh85_externo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh85_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhemitecontracheque set ";
     $virgula = "";
     if(trim($this->rh85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"])){ 
       $sql  .= $virgula." rh85_sequencial = $this->rh85_sequencial ";
       $virgula = ",";
       if(trim($this->rh85_sequencial) == null ){ 
         $this->erro_sql = " Campo rh85_sequencial nao Informado.";
         $this->erro_campo = "rh85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_regist"])){ 
       $sql  .= $virgula." rh85_regist = $this->rh85_regist ";
       $virgula = ",";
       if(trim($this->rh85_regist) == null ){ 
         $this->erro_sql = " Campo rh85_regist nao Informado.";
         $this->erro_campo = "rh85_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_anousu"])){ 
       $sql  .= $virgula." rh85_anousu = $this->rh85_anousu ";
       $virgula = ",";
       if(trim($this->rh85_anousu) == null ){ 
         $this->erro_sql = " Campo rh85_anousu nao Informado.";
         $this->erro_campo = "rh85_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_mesusu"])){ 
       $sql  .= $virgula." rh85_mesusu = $this->rh85_mesusu ";
       $virgula = ",";
       if(trim($this->rh85_mesusu) == null ){ 
         $this->erro_sql = " Campo rh85_mesusu nao Informado.";
         $this->erro_campo = "rh85_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"] !="") ){ 
       $sql  .= $virgula." rh85_dataemissao = '$this->rh85_dataemissao' ";
       $virgula = ",";
       if(trim($this->rh85_dataemissao) == null ){ 
         $this->erro_sql = " Campo rh85_dataemissao nao Informado.";
         $this->erro_campo = "rh85_dataemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"])){ 
         $sql  .= $virgula." rh85_dataemissao = null ";
         $virgula = ",";
         if(trim($this->rh85_dataemissao) == null ){ 
           $this->erro_sql = " Campo rh85_dataemissao nao Informado.";
           $this->erro_campo = "rh85_dataemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh85_horaemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_horaemissao"])){ 
       $sql  .= $virgula." rh85_horaemissao = '$this->rh85_horaemissao' ";
       $virgula = ",";
       if(trim($this->rh85_horaemissao) == null ){ 
         $this->erro_sql = " Campo rh85_horaemissao nao Informado.";
         $this->erro_campo = "rh85_horaemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_sigla"])){ 
       $sql  .= $virgula." rh85_sigla = '$this->rh85_sigla' ";
       $virgula = ",";
       if(trim($this->rh85_sigla) == null ){ 
         $this->erro_sql = " Campo rh85_sigla nao Informado.";
         $this->erro_campo = "rh85_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_codautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_codautent"])){ 
       $sql  .= $virgula." rh85_codautent = '$this->rh85_codautent' ";
       $virgula = ",";
       if(trim($this->rh85_codautent) == null ){ 
         $this->erro_sql = " Campo rh85_codautent nao Informado.";
         $this->erro_campo = "rh85_codautent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_ip"])){ 
       $sql  .= $virgula." rh85_ip = '$this->rh85_ip' ";
       $virgula = ",";
       if(trim($this->rh85_ip) == null ){ 
         $this->erro_sql = " Campo rh85_ip nao Informado.";
         $this->erro_campo = "rh85_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_externo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_externo"])){ 
       $sql  .= $virgula." rh85_externo = '$this->rh85_externo' ";
       $virgula = ",";
       if(trim($this->rh85_externo) == null ){ 
         $this->erro_sql = " Campo rh85_externo nao Informado.";
         $this->erro_campo = "rh85_externo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh85_sequencial!=null){
       $sql .= " rh85_sequencial = $this->rh85_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh85_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14571,'$this->rh85_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"]) || $this->rh85_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2563,14571,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_sequencial'))."','$this->rh85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_regist"]) || $this->rh85_regist != "")
           $resac = db_query("insert into db_acount values($acount,2563,14562,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_regist'))."','$this->rh85_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_anousu"]) || $this->rh85_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2563,14563,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_anousu'))."','$this->rh85_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_mesusu"]) || $this->rh85_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2563,14564,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_mesusu'))."','$this->rh85_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao"]) || $this->rh85_dataemissao != "")
           $resac = db_query("insert into db_acount values($acount,2563,14565,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_dataemissao'))."','$this->rh85_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_horaemissao"]) || $this->rh85_horaemissao != "")
           $resac = db_query("insert into db_acount values($acount,2563,14566,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_horaemissao'))."','$this->rh85_horaemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_sigla"]) || $this->rh85_sigla != "")
           $resac = db_query("insert into db_acount values($acount,2563,14567,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_sigla'))."','$this->rh85_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_codautent"]) || $this->rh85_codautent != "")
           $resac = db_query("insert into db_acount values($acount,2563,14568,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_codautent'))."','$this->rh85_codautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_ip"]) || $this->rh85_ip != "")
           $resac = db_query("insert into db_acount values($acount,2563,14569,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_ip'))."','$this->rh85_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_externo"]) || $this->rh85_externo != "")
           $resac = db_query("insert into db_acount values($acount,2563,14570,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_externo'))."','$this->rh85_externo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemitecontracheque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhemitecontracheque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh85_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh85_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14571,'$rh85_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2563,14571,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14562,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14563,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14564,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14565,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14566,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_horaemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14567,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14568,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_codautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14569,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14570,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_externo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhemitecontracheque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh85_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh85_sequencial = $rh85_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemitecontracheque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhemitecontracheque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhemitecontracheque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemitecontracheque ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh85_sequencial!=null ){
         $sql2 .= " where rhemitecontracheque.rh85_sequencial = $rh85_sequencial "; 
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
   function sql_query_file ( $rh85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemitecontracheque ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh85_sequencial!=null ){
         $sql2 .= " where rhemitecontracheque.rh85_sequencial = $rh85_sequencial "; 
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