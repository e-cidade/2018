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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscal
class cl_fiscal { 
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
   var $y30_codnoti = 0; 
   var $y30_data_dia = null; 
   var $y30_data_mes = null; 
   var $y30_data_ano = null; 
   var $y30_data = null; 
   var $y30_hora = null; 
   var $y30_obs = null; 
   var $y30_setor = 0; 
   var $y30_nome = null; 
   var $y30_dtvenc_dia = null; 
   var $y30_dtvenc_mes = null; 
   var $y30_dtvenc_ano = null; 
   var $y30_dtvenc = null; 
   var $y30_numbloco = null; 
   var $y30_prazorec_dia = null; 
   var $y30_prazorec_mes = null; 
   var $y30_prazorec_ano = null; 
   var $y30_prazorec = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y30_codnoti = int8 = Código da Notificação 
                 y30_data = date = Data da Notificação 
                 y30_hora = char(5) = Hora da Notificação 
                 y30_obs = text = Observação da Notificação 
                 y30_setor = int4 = Código do Departamento 
                 y30_nome = varchar(50) = Nome da Pessoa Notificada 
                 y30_dtvenc = date = Vencimento Atualizada 
                 y30_numbloco = varchar(20) = Número do Bloco 
                 y30_prazorec = date = Prazo p/ Recurso 
                 ";
   //funcao construtor da classe 
   function cl_fiscal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscal"); 
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
       $this->y30_codnoti = ($this->y30_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_codnoti"]:$this->y30_codnoti);
       if($this->y30_data == ""){
         $this->y30_data_dia = ($this->y30_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_data_dia"]:$this->y30_data_dia);
         $this->y30_data_mes = ($this->y30_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_data_mes"]:$this->y30_data_mes);
         $this->y30_data_ano = ($this->y30_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_data_ano"]:$this->y30_data_ano);
         if($this->y30_data_dia != ""){
            $this->y30_data = $this->y30_data_ano."-".$this->y30_data_mes."-".$this->y30_data_dia;
         }
       }
       $this->y30_hora = ($this->y30_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_hora"]:$this->y30_hora);
       $this->y30_obs = ($this->y30_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_obs"]:$this->y30_obs);
       $this->y30_setor = ($this->y30_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_setor"]:$this->y30_setor);
       $this->y30_nome = ($this->y30_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_nome"]:$this->y30_nome);
       if($this->y30_dtvenc == ""){
         $this->y30_dtvenc_dia = ($this->y30_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_dtvenc_dia"]:$this->y30_dtvenc_dia);
         $this->y30_dtvenc_mes = ($this->y30_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_dtvenc_mes"]:$this->y30_dtvenc_mes);
         $this->y30_dtvenc_ano = ($this->y30_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_dtvenc_ano"]:$this->y30_dtvenc_ano);
         if($this->y30_dtvenc_dia != ""){
            $this->y30_dtvenc = $this->y30_dtvenc_ano."-".$this->y30_dtvenc_mes."-".$this->y30_dtvenc_dia;
         }
       }
       $this->y30_numbloco = ($this->y30_numbloco == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_numbloco"]:$this->y30_numbloco);
       if($this->y30_prazorec == ""){
         $this->y30_prazorec_dia = ($this->y30_prazorec_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_prazorec_dia"]:$this->y30_prazorec_dia);
         $this->y30_prazorec_mes = ($this->y30_prazorec_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_prazorec_mes"]:$this->y30_prazorec_mes);
         $this->y30_prazorec_ano = ($this->y30_prazorec_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_prazorec_ano"]:$this->y30_prazorec_ano);
         if($this->y30_prazorec_dia != ""){
            $this->y30_prazorec = $this->y30_prazorec_ano."-".$this->y30_prazorec_mes."-".$this->y30_prazorec_dia;
         }
       }
     }else{
       $this->y30_codnoti = ($this->y30_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y30_codnoti"]:$this->y30_codnoti);
     }
   }
   // funcao para inclusao
   function incluir ($y30_codnoti){ 
      $this->atualizacampos();
     if($this->y30_data == null ){ 
       $this->erro_sql = " Campo Data da Notificação nao Informado.";
       $this->erro_campo = "y30_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y30_hora == null ){ 
       $this->erro_sql = " Campo Hora da Notificação nao Informado.";
       $this->erro_campo = "y30_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y30_setor == null ){ 
       $this->erro_sql = " Campo Código do Departamento nao Informado.";
       $this->erro_campo = "y30_setor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y30_nome == null ){ 
       $this->erro_sql = " Campo Nome da Pessoa Notificada nao Informado.";
       $this->erro_campo = "y30_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y30_dtvenc == null ){ 
       $this->y30_dtvenc = "null";
     }
     if($this->y30_numbloco == null ){ 
       $this->erro_sql = " Campo Número do Bloco nao Informado.";
       $this->erro_campo = "y30_numbloco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y30_prazorec == null ){ 
       $this->y30_prazorec = "null";
     }
     if($y30_codnoti == "" || $y30_codnoti == null ){
       $result = @pg_query("select nextval('fiscal_y30_codnoti_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fiscal_y30_codnoti_seq do campo: y30_codnoti"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y30_codnoti = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from fiscal_y30_codnoti_seq");
       if(($result != false) && (pg_result($result,0,0) < $y30_codnoti)){
         $this->erro_sql = " Campo y30_codnoti maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y30_codnoti = $y30_codnoti; 
       }
     }
     if(($this->y30_codnoti == null) || ($this->y30_codnoti == "") ){ 
       $this->erro_sql = " Campo y30_codnoti nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscal(
                                       y30_codnoti 
                                      ,y30_data 
                                      ,y30_hora 
                                      ,y30_obs 
                                      ,y30_setor 
                                      ,y30_nome 
                                      ,y30_dtvenc 
                                      ,y30_numbloco 
                                      ,y30_prazorec 
                       )
                values (
                                $this->y30_codnoti 
                               ,".($this->y30_data == "null" || $this->y30_data == ""?"null":"'".$this->y30_data."'")." 
                               ,'$this->y30_hora' 
                               ,'$this->y30_obs' 
                               ,$this->y30_setor 
                               ,'$this->y30_nome' 
                               ,".($this->y30_dtvenc == "null" || $this->y30_dtvenc == ""?"null":"'".$this->y30_dtvenc."'")." 
                               ,'$this->y30_numbloco' 
                               ,".($this->y30_prazorec == "null" || $this->y30_prazorec == ""?"null":"'".$this->y30_prazorec."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscal ($this->y30_codnoti) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscal ($this->y30_codnoti) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y30_codnoti;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y30_codnoti));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4941,'$this->y30_codnoti','I')");
       $resac = pg_query("insert into db_acount values($acount,683,4941,'','".AddSlashes(pg_result($resaco,0,'y30_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,4942,'','".AddSlashes(pg_result($resaco,0,'y30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,4943,'','".AddSlashes(pg_result($resaco,0,'y30_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,4944,'','".AddSlashes(pg_result($resaco,0,'y30_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,4945,'','".AddSlashes(pg_result($resaco,0,'y30_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,4946,'','".AddSlashes(pg_result($resaco,0,'y30_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,4948,'','".AddSlashes(pg_result($resaco,0,'y30_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,5088,'','".AddSlashes(pg_result($resaco,0,'y30_numbloco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,683,6787,'','".AddSlashes(pg_result($resaco,0,'y30_prazorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y30_codnoti=null) { 
      $this->atualizacampos();
     $sql = " update fiscal set ";
     $virgula = "";
     if(trim($this->y30_codnoti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_codnoti"])){ 
       $sql  .= $virgula." y30_codnoti = $this->y30_codnoti ";
       $virgula = ",";
       if(trim($this->y30_codnoti) == null ){ 
         $this->erro_sql = " Campo Código da Notificação nao Informado.";
         $this->erro_campo = "y30_codnoti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y30_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y30_data_dia"] !="") ){ 
       $sql  .= $virgula." y30_data = '$this->y30_data' ";
       $virgula = ",";
       if(trim($this->y30_data) == null ){ 
         $this->erro_sql = " Campo Data da Notificação nao Informado.";
         $this->erro_campo = "y30_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y30_data_dia"])){ 
         $sql  .= $virgula." y30_data = null ";
         $virgula = ",";
         if(trim($this->y30_data) == null ){ 
           $this->erro_sql = " Campo Data da Notificação nao Informado.";
           $this->erro_campo = "y30_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y30_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_hora"])){ 
       $sql  .= $virgula." y30_hora = '$this->y30_hora' ";
       $virgula = ",";
       if(trim($this->y30_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Notificação nao Informado.";
         $this->erro_campo = "y30_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y30_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_obs"])){ 
       $sql  .= $virgula." y30_obs = '$this->y30_obs' ";
       $virgula = ",";
     }
     if(trim($this->y30_setor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_setor"])){ 
       $sql  .= $virgula." y30_setor = $this->y30_setor ";
       $virgula = ",";
       if(trim($this->y30_setor) == null ){ 
         $this->erro_sql = " Campo Código do Departamento nao Informado.";
         $this->erro_campo = "y30_setor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y30_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_nome"])){ 
       $sql  .= $virgula." y30_nome = '$this->y30_nome' ";
       $virgula = ",";
       if(trim($this->y30_nome) == null ){ 
         $this->erro_sql = " Campo Nome da Pessoa Notificada nao Informado.";
         $this->erro_campo = "y30_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y30_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y30_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." y30_dtvenc = '$this->y30_dtvenc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y30_dtvenc_dia"])){ 
         $sql  .= $virgula." y30_dtvenc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->y30_numbloco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_numbloco"])){ 
       $sql  .= $virgula." y30_numbloco = '$this->y30_numbloco' ";
       $virgula = ",";
       if(trim($this->y30_numbloco) == null ){ 
         $this->erro_sql = " Campo Número do Bloco nao Informado.";
         $this->erro_campo = "y30_numbloco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y30_prazorec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y30_prazorec_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y30_prazorec_dia"] !="") ){ 
       $sql  .= $virgula." y30_prazorec = '$this->y30_prazorec' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y30_prazorec_dia"])){ 
         $sql  .= $virgula." y30_prazorec = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($y30_codnoti!=null){
       $sql .= " y30_codnoti = $this->y30_codnoti";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y30_codnoti));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,4941,'$this->y30_codnoti','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_codnoti"]))
           $resac = pg_query("insert into db_acount values($acount,683,4941,'".AddSlashes(pg_result($resaco,$conresaco,'y30_codnoti'))."','$this->y30_codnoti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_data"]))
           $resac = pg_query("insert into db_acount values($acount,683,4942,'".AddSlashes(pg_result($resaco,$conresaco,'y30_data'))."','$this->y30_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_hora"]))
           $resac = pg_query("insert into db_acount values($acount,683,4943,'".AddSlashes(pg_result($resaco,$conresaco,'y30_hora'))."','$this->y30_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_obs"]))
           $resac = pg_query("insert into db_acount values($acount,683,4944,'".AddSlashes(pg_result($resaco,$conresaco,'y30_obs'))."','$this->y30_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_setor"]))
           $resac = pg_query("insert into db_acount values($acount,683,4945,'".AddSlashes(pg_result($resaco,$conresaco,'y30_setor'))."','$this->y30_setor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_nome"]))
           $resac = pg_query("insert into db_acount values($acount,683,4946,'".AddSlashes(pg_result($resaco,$conresaco,'y30_nome'))."','$this->y30_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_dtvenc"]))
           $resac = pg_query("insert into db_acount values($acount,683,4948,'".AddSlashes(pg_result($resaco,$conresaco,'y30_dtvenc'))."','$this->y30_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_numbloco"]))
           $resac = pg_query("insert into db_acount values($acount,683,5088,'".AddSlashes(pg_result($resaco,$conresaco,'y30_numbloco'))."','$this->y30_numbloco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y30_prazorec"]))
           $resac = pg_query("insert into db_acount values($acount,683,6787,'".AddSlashes(pg_result($resaco,$conresaco,'y30_prazorec'))."','$this->y30_prazorec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y30_codnoti;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y30_codnoti;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y30_codnoti;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y30_codnoti=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y30_codnoti));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,4941,'$y30_codnoti','E')");
         $resac = pg_query("insert into db_acount values($acount,683,4941,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,4942,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,4943,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,4944,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,4945,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,4946,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,4948,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,5088,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_numbloco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,683,6787,'','".AddSlashes(pg_result($resaco,$iresaco,'y30_prazorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y30_codnoti != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y30_codnoti = $y30_codnoti ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y30_codnoti;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y30_codnoti;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y30_codnoti;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:fiscal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $y30_codnoti=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscal ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscal.y30_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y30_codnoti!=null ){
         $sql2 .= " where fiscal.y30_codnoti = $y30_codnoti "; 
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
   function sql_query_file ( $y30_codnoti=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscal ";
     $sql2 = "";
     if($dbwhere==""){
       if($y30_codnoti!=null ){
         $sql2 .= " where fiscal.y30_codnoti = $y30_codnoti "; 
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
   function sql_querycgm ( $y30_codnoti=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     $sql .= "          case when q02_numcgm is not null then q02_numcgm else
                                (case when j01_numcgm is not null then j01_numcgm else
                                        (case when y80_numcgm is not null then y80_numcgm else q02_numcgm
                                        end )
                                end )
                        end as z01_numcgm ,
                        case when y70_codvist is not null then y70_codvist
                        end as y70_codvist
                        from fiscal
                                left join fiscalcgm on y36_codnoti = y30_codnoti
                                left join fiscalinscr on y34_codnoti = y30_codnoti
                                left join fiscalmatric on y35_codnoti = y30_codnoti
                                left join fiscalsanitario on y37_codnoti = y30_codnoti
                                left join iptubase on j01_matric = y35_matric
                                left join issbase on y34_inscr = q02_inscr
                                left join sanitario on y80_codsani = y37_codsani
                                left join fiscalvistorias on y21_codnoti = y30_codnoti
                                left join vistorias on y70_codvist = y21_codvist
     ";
     $sql2 = "";
     if($y30_codnoti!=null ){
       $sql2 .= " where fiscal.y30_codnoti = $y30_codnoti ";
     }
     $sql .= $sql2;
     return $sql;
  }
   function sql_query_busca($y30_codnoti=null,$dbwhere=""){

    $sql = "select dl_noti, dl_identifica, dl_codigo, z01_nome, y30_setor, y30_data, y30_prazorec, y30_dtvenc, nome as fiscal, descrdepto as departamento from
			   (select y30_setor, nome, descrdepto,
                           y30_codnoti as dl_noti, y30_data, y30_prazorec, y30_dtvenc,
			   case when q02_numcgm is not null then 'Inscrição'  else
                                 (case when j01_numcgm is not null then 'Matrícula' else
                                        (case when y80_numcgm is not null then 'Sanitário '  else
                                                (case when z01_numcgm is not null then 'Cgm' else 
                    				     (case when y30_codnoti is not null then 'Notificação' else 'Nenhum'
						      end)
                                                end)
                                        end )
                                end )
                        end as dl_identifica,
                        case when y34_inscr is not null then y34_inscr else
                                (case when y35_matric is not null then y35_matric else
                                        (case when y37_codsani is not null then y37_codsani else
                                                (case when z01_numcgm is not null then z01_numcgm else 
						     (case when y51_codnoti is not null then y51_codnoti 
						      end)
                                                end)
                                        end )
                                end )
                        end as dl_codigo,
                        case when q02_numcgm is not null then q02_numcgm else
                                (case when j01_numcgm is not null then j01_numcgm else
                                        (case when y80_numcgm is not null then y80_numcgm else
                                                (case when z01_numcgm is not null then z01_numcgm else q02_numcgm
                                                end)
                                        end )
                                end )
                        end as z01_numcgm
                from fiscal
                        left join fiscalcgm on  y36_codnoti= y30_codnoti
                        left join fiscalinscr on y34_codnoti = y30_codnoti
                        left join fiscalmatric on y35_codnoti = y30_codnoti
                        left join fiscalsanitario on y37_codnoti = y30_codnoti
                        left join iptubase on j01_matric = y35_matric
                        left join issbase on y34_inscr = q02_inscr
                        left join cgm on z01_numcgm = y36_numcgm
                        left join sanitario on y80_codsani = y37_codsani
                        left join autofiscal on y51_codnoti = y30_codnoti
                        left join fiscalusuario on y38_codnoti = y30_codnoti
                        left join db_usuarios on id_usuario = y38_id_usuario
                        inner join db_depart on coddepto = y30_setor
	        ) as x 
		    inner join cgm on cgm.z01_numcgm=x.z01_numcgm";
                       /* join fiscalusuario on y38_codnoti = y30_codnoti
			 join fiscalusuario on y38_idusuario = id_usuario*/
     $sql2 = "";
     if($dbwhere==""){
       if($y30_codnoti!=null ){
         $sql2 .= " where dl_noti = $y30_codnoti ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     return $sql;
  }
}
?>