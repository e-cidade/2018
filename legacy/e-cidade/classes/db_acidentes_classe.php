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

//MODULO: transito
//CLASSE DA ENTIDADE acidentes
class cl_acidentes { 
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
   var $tr07_id = 0; 
   var $tr07_tipoacid = 0; 
   var $tr07_idpista = 0; 
   var $tr07_idtempo = 0; 
   var $tr07_hora = null; 
   var $tr07_data_dia = null; 
   var $tr07_data_mes = null; 
   var $tr07_data_ano = null; 
   var $tr07_data = null; 
   var $tr07_local1 = 0; 
   var $tr07_local2 = 0; 
   var $tr07_idcausa = 0; 
   var $tr07_idbairro = 0; 
   var $tr07_esquina = 0; 
   var $tr07_depto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tr07_id = int8 = Código do Acidente 
                 tr07_tipoacid = int8 = Tipo Acidente 
                 tr07_idpista = int8 = Condições da Pista 
                 tr07_idtempo = int8 = Condições Climáticas 
                 tr07_hora = char(5) = Hora do Acidente 
                 tr07_data = date = Data 
                 tr07_local1 = int8 = Local do Acidente 
                 tr07_local2 = int8 = Esquina OU Número 
                 tr07_idcausa = int8 = Causa do Acidente 
                 tr07_idbairro = int8 = Bairro 
                 tr07_esquina = int4 = Esquina 
                 tr07_depto = int8 = Departamento 
                 ";
   //funcao construtor da classe 
   function cl_acidentes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acidentes"); 
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
       $this->tr07_id = ($this->tr07_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_id"]:$this->tr07_id);
       $this->tr07_tipoacid = ($this->tr07_tipoacid == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_tipoacid"]:$this->tr07_tipoacid);
       $this->tr07_idpista = ($this->tr07_idpista == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_idpista"]:$this->tr07_idpista);
       $this->tr07_idtempo = ($this->tr07_idtempo == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_idtempo"]:$this->tr07_idtempo);
       $this->tr07_hora = ($this->tr07_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_hora"]:$this->tr07_hora);
       if($this->tr07_data == ""){
         $this->tr07_data_dia = ($this->tr07_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_data_dia"]:$this->tr07_data_dia);
         $this->tr07_data_mes = ($this->tr07_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_data_mes"]:$this->tr07_data_mes);
         $this->tr07_data_ano = ($this->tr07_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_data_ano"]:$this->tr07_data_ano);
         if($this->tr07_data_dia != ""){
            $this->tr07_data = $this->tr07_data_ano."-".$this->tr07_data_mes."-".$this->tr07_data_dia;
         }
       }
       $this->tr07_local1 = ($this->tr07_local1 == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_local1"]:$this->tr07_local1);
       $this->tr07_local2 = ($this->tr07_local2 == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_local2"]:$this->tr07_local2);
       $this->tr07_idcausa = ($this->tr07_idcausa == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_idcausa"]:$this->tr07_idcausa);
       $this->tr07_idbairro = ($this->tr07_idbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_idbairro"]:$this->tr07_idbairro);
       $this->tr07_esquina = ($this->tr07_esquina == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_esquina"]:$this->tr07_esquina);
       $this->tr07_depto = ($this->tr07_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_depto"]:$this->tr07_depto);
     }else{
       $this->tr07_id = ($this->tr07_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr07_id"]:$this->tr07_id);
     }
   }
   // funcao para inclusao
   function incluir ($tr07_id){ 
      $this->atualizacampos();
     if($this->tr07_tipoacid == null ){ 
       $this->erro_sql = " Campo Tipo Acidente nao Informado.";
       $this->erro_campo = "tr07_tipoacid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_idpista == null ){ 
       $this->erro_sql = " Campo Condições da Pista nao Informado.";
       $this->erro_campo = "tr07_idpista";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_idtempo == null ){ 
       $this->erro_sql = " Campo Condições Climáticas nao Informado.";
       $this->erro_campo = "tr07_idtempo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_hora == null ){ 
       $this->tr07_hora = "0";
     }
     if($this->tr07_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tr07_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_local1 == null ){ 
       $this->erro_sql = " Campo Local do Acidente nao Informado.";
       $this->erro_campo = "tr07_local1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_local2 == null ){ 
       $this->erro_sql = " Campo Esquina OU Número nao Informado.";
       $this->erro_campo = "tr07_local2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_idcausa == null ){ 
       $this->erro_sql = " Campo Causa do Acidente nao Informado.";
       $this->erro_campo = "tr07_idcausa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_idbairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "tr07_idbairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr07_esquina == null ){ 
       $this->tr07_esquina = "0";
     }
     if($this->tr07_depto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "tr07_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tr07_id == "" || $tr07_id == null ){
       $result = db_query("select nextval('acidentes_tr07_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acidentes_tr07_id_seq do campo: tr07_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tr07_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acidentes_tr07_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $tr07_id)){
         $this->erro_sql = " Campo tr07_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tr07_id = $tr07_id; 
       }
     }
     if(($this->tr07_id == null) || ($this->tr07_id == "") ){ 
       $this->erro_sql = " Campo tr07_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acidentes(
                                       tr07_id 
                                      ,tr07_tipoacid 
                                      ,tr07_idpista 
                                      ,tr07_idtempo 
                                      ,tr07_hora 
                                      ,tr07_data 
                                      ,tr07_local1 
                                      ,tr07_local2 
                                      ,tr07_idcausa 
                                      ,tr07_idbairro 
                                      ,tr07_esquina 
                                      ,tr07_depto 
                       )
                values (
                                $this->tr07_id 
                               ,$this->tr07_tipoacid 
                               ,$this->tr07_idpista 
                               ,$this->tr07_idtempo 
                               ,'$this->tr07_hora' 
                               ,".($this->tr07_data == "null" || $this->tr07_data == ""?"null":"'".$this->tr07_data."'")." 
                               ,$this->tr07_local1 
                               ,$this->tr07_local2 
                               ,$this->tr07_idcausa 
                               ,$this->tr07_idbairro 
                               ,$this->tr07_esquina 
                               ,$this->tr07_depto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acidentes ($this->tr07_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acidentes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acidentes ($this->tr07_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr07_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tr07_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5623,'$this->tr07_id','I')");
       $resac = db_query("insert into db_acount values($acount,874,5623,'','".AddSlashes(pg_result($resaco,0,'tr07_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5630,'','".AddSlashes(pg_result($resaco,0,'tr07_tipoacid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5626,'','".AddSlashes(pg_result($resaco,0,'tr07_idpista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5627,'','".AddSlashes(pg_result($resaco,0,'tr07_idtempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5622,'','".AddSlashes(pg_result($resaco,0,'tr07_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5619,'','".AddSlashes(pg_result($resaco,0,'tr07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5628,'','".AddSlashes(pg_result($resaco,0,'tr07_local1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5629,'','".AddSlashes(pg_result($resaco,0,'tr07_local2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5625,'','".AddSlashes(pg_result($resaco,0,'tr07_idcausa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5624,'','".AddSlashes(pg_result($resaco,0,'tr07_idbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5621,'','".AddSlashes(pg_result($resaco,0,'tr07_esquina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,874,5620,'','".AddSlashes(pg_result($resaco,0,'tr07_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tr07_id=null) { 
      $this->atualizacampos();
     $sql = " update acidentes set ";
     $virgula = "";
     if(trim($this->tr07_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_id"])){ 
       $sql  .= $virgula." tr07_id = $this->tr07_id ";
       $virgula = ",";
       if(trim($this->tr07_id) == null ){ 
         $this->erro_sql = " Campo Código do Acidente nao Informado.";
         $this->erro_campo = "tr07_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_tipoacid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_tipoacid"])){ 
       $sql  .= $virgula." tr07_tipoacid = $this->tr07_tipoacid ";
       $virgula = ",";
       if(trim($this->tr07_tipoacid) == null ){ 
         $this->erro_sql = " Campo Tipo Acidente nao Informado.";
         $this->erro_campo = "tr07_tipoacid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_idpista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_idpista"])){ 
       $sql  .= $virgula." tr07_idpista = $this->tr07_idpista ";
       $virgula = ",";
       if(trim($this->tr07_idpista) == null ){ 
         $this->erro_sql = " Campo Condições da Pista nao Informado.";
         $this->erro_campo = "tr07_idpista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_idtempo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_idtempo"])){ 
       $sql  .= $virgula." tr07_idtempo = $this->tr07_idtempo ";
       $virgula = ",";
       if(trim($this->tr07_idtempo) == null ){ 
         $this->erro_sql = " Campo Condições Climáticas nao Informado.";
         $this->erro_campo = "tr07_idtempo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_hora"])){ 
       $sql  .= $virgula." tr07_hora = '$this->tr07_hora' ";
       $virgula = ",";
     }
     if(trim($this->tr07_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tr07_data_dia"] !="") ){ 
       $sql  .= $virgula." tr07_data = '$this->tr07_data' ";
       $virgula = ",";
       if(trim($this->tr07_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tr07_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_data_dia"])){ 
         $sql  .= $virgula." tr07_data = null ";
         $virgula = ",";
         if(trim($this->tr07_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tr07_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tr07_local1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_local1"])){ 
       $sql  .= $virgula." tr07_local1 = $this->tr07_local1 ";
       $virgula = ",";
       if(trim($this->tr07_local1) == null ){ 
         $this->erro_sql = " Campo Local do Acidente nao Informado.";
         $this->erro_campo = "tr07_local1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_local2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_local2"])){ 
       $sql  .= $virgula." tr07_local2 = $this->tr07_local2 ";
       $virgula = ",";
       if(trim($this->tr07_local2) == null ){ 
         $this->erro_sql = " Campo Esquina OU Número nao Informado.";
         $this->erro_campo = "tr07_local2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_idcausa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_idcausa"])){ 
       $sql  .= $virgula." tr07_idcausa = $this->tr07_idcausa ";
       $virgula = ",";
       if(trim($this->tr07_idcausa) == null ){ 
         $this->erro_sql = " Campo Causa do Acidente nao Informado.";
         $this->erro_campo = "tr07_idcausa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_idbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_idbairro"])){ 
       $sql  .= $virgula." tr07_idbairro = $this->tr07_idbairro ";
       $virgula = ",";
       if(trim($this->tr07_idbairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "tr07_idbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr07_esquina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_esquina"])){ 
        if(trim($this->tr07_esquina)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tr07_esquina"])){ 
           $this->tr07_esquina = "0" ; 
        } 
       $sql  .= $virgula." tr07_esquina = $this->tr07_esquina ";
       $virgula = ",";
     }
     if(trim($this->tr07_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr07_depto"])){ 
       $sql  .= $virgula." tr07_depto = $this->tr07_depto ";
       $virgula = ",";
       if(trim($this->tr07_depto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "tr07_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tr07_id!=null){
       $sql .= " tr07_id = $this->tr07_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tr07_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5623,'$this->tr07_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_id"]))
           $resac = db_query("insert into db_acount values($acount,874,5623,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_id'))."','$this->tr07_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_tipoacid"]))
           $resac = db_query("insert into db_acount values($acount,874,5630,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_tipoacid'))."','$this->tr07_tipoacid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_idpista"]))
           $resac = db_query("insert into db_acount values($acount,874,5626,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_idpista'))."','$this->tr07_idpista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_idtempo"]))
           $resac = db_query("insert into db_acount values($acount,874,5627,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_idtempo'))."','$this->tr07_idtempo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_hora"]))
           $resac = db_query("insert into db_acount values($acount,874,5622,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_hora'))."','$this->tr07_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_data"]))
           $resac = db_query("insert into db_acount values($acount,874,5619,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_data'))."','$this->tr07_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_local1"]))
           $resac = db_query("insert into db_acount values($acount,874,5628,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_local1'))."','$this->tr07_local1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_local2"]))
           $resac = db_query("insert into db_acount values($acount,874,5629,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_local2'))."','$this->tr07_local2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_idcausa"]))
           $resac = db_query("insert into db_acount values($acount,874,5625,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_idcausa'))."','$this->tr07_idcausa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_idbairro"]))
           $resac = db_query("insert into db_acount values($acount,874,5624,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_idbairro'))."','$this->tr07_idbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_esquina"]))
           $resac = db_query("insert into db_acount values($acount,874,5621,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_esquina'))."','$this->tr07_esquina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr07_depto"]))
           $resac = db_query("insert into db_acount values($acount,874,5620,'".AddSlashes(pg_result($resaco,$conresaco,'tr07_depto'))."','$this->tr07_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acidentes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr07_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acidentes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr07_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr07_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tr07_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tr07_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5623,'$tr07_id','E')");
         $resac = db_query("insert into db_acount values($acount,874,5623,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5630,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_tipoacid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5626,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_idpista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5627,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_idtempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5622,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5619,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5628,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_local1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5629,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_local2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5625,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_idcausa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5624,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_idbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5621,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_esquina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,874,5620,'','".AddSlashes(pg_result($resaco,$iresaco,'tr07_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acidentes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tr07_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr07_id = $tr07_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acidentes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tr07_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acidentes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tr07_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tr07_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:acidentes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tr07_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acidentes ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = acidentes.tr07_idbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = acidentes.tr07_local1";
     $sql .= "      inner join causas  on  causas.tr02_id = acidentes.tr07_idcausa";
     $sql .= "      inner join tipo_tempo  on  tipo_tempo.tr04_id = acidentes.tr07_idtempo";
     $sql .= "      inner join tipo_acidentes  on  tipo_acidentes.tr01_id = acidentes.tr07_tipoacid";
     $sql .= "      inner join tipo_pista  on  tipo_pista.tr03_id = acidentes.tr07_idpista";
     $sql2 = "";
     if($dbwhere==""){
       if($tr07_id!=null ){
         $sql2 .= " where acidentes.tr07_id = $tr07_id "; 
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
   function sql_query_file ( $tr07_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acidentes ";
     $sql2 = "";
     if($dbwhere==""){
       if($tr07_id!=null ){
         $sql2 .= " where acidentes.tr07_id = $tr07_id "; 
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
   function sql_leftquery ( $tr07_id=null,$campos="*",$ordem=null,$dbwhere=""){
    // echo $dbwhere;
    // exit;
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
     $sql .= " from acidentes ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = acidentes.tr07_idbairro";
     $sql .= "      inner join ruas r1  on  r1.j14_codigo = acidentes.tr07_local1";
     $sql .= "      inner join causas  on  causas.tr02_id = acidentes.tr07_idcausa";
     $sql .= "      inner join tipo_acidentes  on  tipo_acidentes.tr01_id = acidentes.tr07_tipoacid";
     $sql .= "      inner join tipo_pista  on  tipo_pista.tr03_id = acidentes.tr07_idpista";
     $sql .= "      inner join tipo_tempo  on  tipo_tempo.tr04_id = acidentes.tr07_idtempo";
     $sql .= "      left outer join ruas r2  on  r2.j14_codigo = acidentes.tr07_local2";
     $sql2 = "";
     if($dbwhere==""){
       if($tr07_id!=null ){
         $sql2 .= " where acidentes.tr07_id = $tr07_id ";
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