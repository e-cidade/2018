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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicitensobrigbaixa
class cl_veicitensobrigbaixa { 
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
   var $ve10_sequencial = 0; 
   var $ve10_veiccaditensobrigtipobaixa = 0; 
   var $ve10_veicitensobrig = 0; 
   var $ve10_data_dia = null; 
   var $ve10_data_mes = null; 
   var $ve10_data_ano = null; 
   var $ve10_data = null; 
   var $ve10_hora = null; 
   var $ve10_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve10_sequencial = int4 = Cód. Sequencial 
                 ve10_veiccaditensobrigtipobaixa = int4 = Tipo de baixa 
                 ve10_veicitensobrig = int4 = Item 
                 ve10_data = date = Data 
                 ve10_hora = char(5) = Hora 
                 ve10_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_veicitensobrigbaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicitensobrigbaixa"); 
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
       $this->ve10_sequencial = ($this->ve10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_sequencial"]:$this->ve10_sequencial);
       $this->ve10_veiccaditensobrigtipobaixa = ($this->ve10_veiccaditensobrigtipobaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_veiccaditensobrigtipobaixa"]:$this->ve10_veiccaditensobrigtipobaixa);
       $this->ve10_veicitensobrig = ($this->ve10_veicitensobrig == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_veicitensobrig"]:$this->ve10_veicitensobrig);
       if($this->ve10_data == ""){
         $this->ve10_data_dia = ($this->ve10_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_data_dia"]:$this->ve10_data_dia);
         $this->ve10_data_mes = ($this->ve10_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_data_mes"]:$this->ve10_data_mes);
         $this->ve10_data_ano = ($this->ve10_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_data_ano"]:$this->ve10_data_ano);
         if($this->ve10_data_dia != ""){
            $this->ve10_data = $this->ve10_data_ano."-".$this->ve10_data_mes."-".$this->ve10_data_dia;
         }
       }
       $this->ve10_hora = ($this->ve10_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_hora"]:$this->ve10_hora);
       $this->ve10_motivo = ($this->ve10_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_motivo"]:$this->ve10_motivo);
     }else{
       $this->ve10_sequencial = ($this->ve10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve10_sequencial"]:$this->ve10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ve10_sequencial){ 
      $this->atualizacampos();
     if($this->ve10_veiccaditensobrigtipobaixa == null ){ 
       $this->erro_sql = " Campo Tipo de baixa nao Informado.";
       $this->erro_campo = "ve10_veiccaditensobrigtipobaixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve10_veicitensobrig == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "ve10_veicitensobrig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve10_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ve10_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve10_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ve10_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve10_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "ve10_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve10_sequencial == "" || $ve10_sequencial == null ){
       $result = db_query("select nextval('veicitensobrigbaixa_ve10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicitensobrigbaixa_ve10_sequencial_seq do campo: ve10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicitensobrigbaixa_ve10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve10_sequencial)){
         $this->erro_sql = " Campo ve10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve10_sequencial = $ve10_sequencial; 
       }
     }
     if(($this->ve10_sequencial == null) || ($this->ve10_sequencial == "") ){ 
       $this->erro_sql = " Campo ve10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicitensobrigbaixa(
                                       ve10_sequencial 
                                      ,ve10_veiccaditensobrigtipobaixa 
                                      ,ve10_veicitensobrig 
                                      ,ve10_data 
                                      ,ve10_hora 
                                      ,ve10_motivo 
                       )
                values (
                                $this->ve10_sequencial 
                               ,$this->ve10_veiccaditensobrigtipobaixa 
                               ,$this->ve10_veicitensobrig 
                               ,".($this->ve10_data == "null" || $this->ve10_data == ""?"null":"'".$this->ve10_data."'")." 
                               ,'$this->ve10_hora' 
                               ,'$this->ve10_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Baixa de itens obrigatórios ($this->ve10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Baixa de itens obrigatórios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Baixa de itens obrigatórios ($this->ve10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve10_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11098,'$this->ve10_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1913,11098,'','".AddSlashes(pg_result($resaco,0,'ve10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1913,11099,'','".AddSlashes(pg_result($resaco,0,'ve10_veiccaditensobrigtipobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1913,11100,'','".AddSlashes(pg_result($resaco,0,'ve10_veicitensobrig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1913,11101,'','".AddSlashes(pg_result($resaco,0,'ve10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1913,11102,'','".AddSlashes(pg_result($resaco,0,'ve10_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1913,11103,'','".AddSlashes(pg_result($resaco,0,'ve10_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update veicitensobrigbaixa set ";
     $virgula = "";
     if(trim($this->ve10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve10_sequencial"])){ 
       $sql  .= $virgula." ve10_sequencial = $this->ve10_sequencial ";
       $virgula = ",";
       if(trim($this->ve10_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "ve10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve10_veiccaditensobrigtipobaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve10_veiccaditensobrigtipobaixa"])){ 
       $sql  .= $virgula." ve10_veiccaditensobrigtipobaixa = $this->ve10_veiccaditensobrigtipobaixa ";
       $virgula = ",";
       if(trim($this->ve10_veiccaditensobrigtipobaixa) == null ){ 
         $this->erro_sql = " Campo Tipo de baixa nao Informado.";
         $this->erro_campo = "ve10_veiccaditensobrigtipobaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve10_veicitensobrig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve10_veicitensobrig"])){ 
       $sql  .= $virgula." ve10_veicitensobrig = $this->ve10_veicitensobrig ";
       $virgula = ",";
       if(trim($this->ve10_veicitensobrig) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "ve10_veicitensobrig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve10_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve10_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve10_data_dia"] !="") ){ 
       $sql  .= $virgula." ve10_data = '$this->ve10_data' ";
       $virgula = ",";
       if(trim($this->ve10_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ve10_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_data_dia"])){ 
         $sql  .= $virgula." ve10_data = null ";
         $virgula = ",";
         if(trim($this->ve10_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ve10_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve10_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve10_hora"])){ 
       $sql  .= $virgula." ve10_hora = '$this->ve10_hora' ";
       $virgula = ",";
       if(trim($this->ve10_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ve10_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve10_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve10_motivo"])){ 
       $sql  .= $virgula." ve10_motivo = '$this->ve10_motivo' ";
       $virgula = ",";
       if(trim($this->ve10_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "ve10_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve10_sequencial!=null){
       $sql .= " ve10_sequencial = $this->ve10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11098,'$this->ve10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1913,11098,'".AddSlashes(pg_result($resaco,$conresaco,'ve10_sequencial'))."','$this->ve10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_veiccaditensobrigtipobaixa"]))
           $resac = db_query("insert into db_acount values($acount,1913,11099,'".AddSlashes(pg_result($resaco,$conresaco,'ve10_veiccaditensobrigtipobaixa'))."','$this->ve10_veiccaditensobrigtipobaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_veicitensobrig"]))
           $resac = db_query("insert into db_acount values($acount,1913,11100,'".AddSlashes(pg_result($resaco,$conresaco,'ve10_veicitensobrig'))."','$this->ve10_veicitensobrig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_data"]))
           $resac = db_query("insert into db_acount values($acount,1913,11101,'".AddSlashes(pg_result($resaco,$conresaco,'ve10_data'))."','$this->ve10_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_hora"]))
           $resac = db_query("insert into db_acount values($acount,1913,11102,'".AddSlashes(pg_result($resaco,$conresaco,'ve10_hora'))."','$this->ve10_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve10_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1913,11103,'".AddSlashes(pg_result($resaco,$conresaco,'ve10_motivo'))."','$this->ve10_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa de itens obrigatórios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa de itens obrigatórios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve10_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve10_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11098,'$ve10_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1913,11098,'','".AddSlashes(pg_result($resaco,$iresaco,'ve10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1913,11099,'','".AddSlashes(pg_result($resaco,$iresaco,'ve10_veiccaditensobrigtipobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1913,11100,'','".AddSlashes(pg_result($resaco,$iresaco,'ve10_veicitensobrig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1913,11101,'','".AddSlashes(pg_result($resaco,$iresaco,'ve10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1913,11102,'','".AddSlashes(pg_result($resaco,$iresaco,'ve10_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1913,11103,'','".AddSlashes(pg_result($resaco,$iresaco,'ve10_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicitensobrigbaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve10_sequencial = $ve10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa de itens obrigatórios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa de itens obrigatórios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicitensobrigbaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicitensobrigbaixa ";
     $sql .= "      inner join veiccaditensobrigtipobaixa  on  veiccaditensobrigtipobaixa.ve11_sequencial = veicitensobrigbaixa.ve10_veiccaditensobrigtipobaixa";
     $sql .= "      inner join veicitensobrig  on  veicitensobrig.ve09_sequencial = veicitensobrigbaixa.ve10_veicitensobrig";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicitensobrig.ve09_veiculos";
     $sql .= "      inner join veiccaditensobrig  on  veiccaditensobrig.ve08_sequencial = veicitensobrig.ve09_veiccaditensobrig";
     $sql2 = "";
     if($dbwhere==""){
       if($ve10_sequencial!=null ){
         $sql2 .= " where veicitensobrigbaixa.ve10_sequencial = $ve10_sequencial "; 
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
   function sql_query_file ( $ve10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicitensobrigbaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve10_sequencial!=null ){
         $sql2 .= " where veicitensobrigbaixa.ve10_sequencial = $ve10_sequencial "; 
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