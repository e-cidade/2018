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

//MODULO: pessoal
//CLASSE DA ENTIDADE eventos
class cl_eventos { 
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
   var $r26_anousu = 0; 
   var $r26_mesusu = 0; 
   var $r26_regist = 0; 
   var $r26_evento = null; 
   var $r26_dtinic_dia = null; 
   var $r26_dtinic_mes = null; 
   var $r26_dtinic_ano = null; 
   var $r26_dtinic = null; 
   var $r26_dtvenc_dia = null; 
   var $r26_dtvenc_mes = null; 
   var $r26_dtvenc_ano = null; 
   var $r26_dtvenc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r26_anousu = int4 = Ano do Exercicio 
                 r26_mesusu = int4 = Mes do Exercicio 
                 r26_regist = int4 = Codigo do Funcionario 
                 r26_evento = varchar(4) = Código do Evento 
                 r26_dtinic = date = Data de Cadastramento d/Evento 
                 r26_dtvenc = date = Data Vencimento do Evento 
                 ";
   //funcao construtor da classe 
   function cl_eventos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("eventos"); 
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
       $this->r26_anousu = ($this->r26_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_anousu"]:$this->r26_anousu);
       $this->r26_mesusu = ($this->r26_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_mesusu"]:$this->r26_mesusu);
       $this->r26_regist = ($this->r26_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_regist"]:$this->r26_regist);
       $this->r26_evento = ($this->r26_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_evento"]:$this->r26_evento);
       if($this->r26_dtinic == ""){
         $this->r26_dtinic_dia = ($this->r26_dtinic_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_dtinic_dia"]:$this->r26_dtinic_dia);
         $this->r26_dtinic_mes = ($this->r26_dtinic_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_dtinic_mes"]:$this->r26_dtinic_mes);
         $this->r26_dtinic_ano = ($this->r26_dtinic_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_dtinic_ano"]:$this->r26_dtinic_ano);
         if($this->r26_dtinic_dia != ""){
            $this->r26_dtinic = $this->r26_dtinic_ano."-".$this->r26_dtinic_mes."-".$this->r26_dtinic_dia;
         }
       }
       if($this->r26_dtvenc == ""){
         $this->r26_dtvenc_dia = ($this->r26_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_dtvenc_dia"]:$this->r26_dtvenc_dia);
         $this->r26_dtvenc_mes = ($this->r26_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_dtvenc_mes"]:$this->r26_dtvenc_mes);
         $this->r26_dtvenc_ano = ($this->r26_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_dtvenc_ano"]:$this->r26_dtvenc_ano);
         if($this->r26_dtvenc_dia != ""){
            $this->r26_dtvenc = $this->r26_dtvenc_ano."-".$this->r26_dtvenc_mes."-".$this->r26_dtvenc_dia;
         }
       }
     }else{
       $this->r26_anousu = ($this->r26_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_anousu"]:$this->r26_anousu);
       $this->r26_mesusu = ($this->r26_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_mesusu"]:$this->r26_mesusu);
       $this->r26_regist = ($this->r26_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_regist"]:$this->r26_regist);
       $this->r26_evento = ($this->r26_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["r26_evento"]:$this->r26_evento);
     }
   }
   // funcao para inclusao
   function incluir ($r26_anousu,$r26_mesusu,$r26_regist,$r26_evento){ 
      $this->atualizacampos();
     if($this->r26_dtinic == null ){ 
       $this->erro_sql = " Campo Data de Cadastramento d/Evento nao Informado.";
       $this->erro_campo = "r26_dtinic_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r26_dtvenc == null ){ 
       $this->erro_sql = " Campo Data Vencimento do Evento nao Informado.";
       $this->erro_campo = "r26_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r26_anousu = $r26_anousu; 
       $this->r26_mesusu = $r26_mesusu; 
       $this->r26_regist = $r26_regist; 
       $this->r26_evento = $r26_evento; 
     if(($this->r26_anousu == null) || ($this->r26_anousu == "") ){ 
       $this->erro_sql = " Campo r26_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r26_mesusu == null) || ($this->r26_mesusu == "") ){ 
       $this->erro_sql = " Campo r26_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r26_regist == null) || ($this->r26_regist == "") ){ 
       $this->erro_sql = " Campo r26_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r26_evento == null) || ($this->r26_evento == "") ){ 
       $this->erro_sql = " Campo r26_evento nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into eventos(
                                       r26_anousu 
                                      ,r26_mesusu 
                                      ,r26_regist 
                                      ,r26_evento 
                                      ,r26_dtinic 
                                      ,r26_dtvenc 
                       )
                values (
                                $this->r26_anousu 
                               ,$this->r26_mesusu 
                               ,$this->r26_regist 
                               ,'$this->r26_evento' 
                               ,".($this->r26_dtinic == "null" || $this->r26_dtinic == ""?"null":"'".$this->r26_dtinic."'")." 
                               ,".($this->r26_dtvenc == "null" || $this->r26_dtvenc == ""?"null":"'".$this->r26_dtvenc."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastramento dos Eventos                          ($this->r26_anousu."-".$this->r26_mesusu."-".$this->r26_regist."-".$this->r26_evento) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastramento dos Eventos                          já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastramento dos Eventos                          ($this->r26_anousu."-".$this->r26_mesusu."-".$this->r26_regist."-".$this->r26_evento) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r26_anousu."-".$this->r26_mesusu."-".$this->r26_regist."-".$this->r26_evento;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r26_anousu,$this->r26_mesusu,$this->r26_regist,$this->r26_evento));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3889,'$this->r26_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3890,'$this->r26_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3891,'$this->r26_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3892,'$this->r26_evento','I')");
       $resac = db_query("insert into db_acount values($acount,547,3889,'','".AddSlashes(pg_result($resaco,0,'r26_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,547,3890,'','".AddSlashes(pg_result($resaco,0,'r26_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,547,3891,'','".AddSlashes(pg_result($resaco,0,'r26_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,547,3892,'','".AddSlashes(pg_result($resaco,0,'r26_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,547,3893,'','".AddSlashes(pg_result($resaco,0,'r26_dtinic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,547,3894,'','".AddSlashes(pg_result($resaco,0,'r26_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r26_anousu=null,$r26_mesusu=null,$r26_regist=null,$r26_evento=null) { 
      $this->atualizacampos();
     $sql = " update eventos set ";
     $virgula = "";
     if(trim($this->r26_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r26_anousu"])){ 
       $sql  .= $virgula." r26_anousu = $this->r26_anousu ";
       $virgula = ",";
       if(trim($this->r26_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r26_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r26_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r26_mesusu"])){ 
       $sql  .= $virgula." r26_mesusu = $this->r26_mesusu ";
       $virgula = ",";
       if(trim($this->r26_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r26_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r26_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r26_regist"])){ 
       $sql  .= $virgula." r26_regist = $this->r26_regist ";
       $virgula = ",";
       if(trim($this->r26_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r26_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r26_evento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r26_evento"])){ 
       $sql  .= $virgula." r26_evento = '$this->r26_evento' ";
       $virgula = ",";
       if(trim($this->r26_evento) == null ){ 
         $this->erro_sql = " Campo Código do Evento nao Informado.";
         $this->erro_campo = "r26_evento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r26_dtinic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r26_dtinic_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r26_dtinic_dia"] !="") ){ 
       $sql  .= $virgula." r26_dtinic = '$this->r26_dtinic' ";
       $virgula = ",";
       if(trim($this->r26_dtinic) == null ){ 
         $this->erro_sql = " Campo Data de Cadastramento d/Evento nao Informado.";
         $this->erro_campo = "r26_dtinic_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r26_dtinic_dia"])){ 
         $sql  .= $virgula." r26_dtinic = null ";
         $virgula = ",";
         if(trim($this->r26_dtinic) == null ){ 
           $this->erro_sql = " Campo Data de Cadastramento d/Evento nao Informado.";
           $this->erro_campo = "r26_dtinic_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r26_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r26_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r26_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." r26_dtvenc = '$this->r26_dtvenc' ";
       $virgula = ",";
       if(trim($this->r26_dtvenc) == null ){ 
         $this->erro_sql = " Campo Data Vencimento do Evento nao Informado.";
         $this->erro_campo = "r26_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r26_dtvenc_dia"])){ 
         $sql  .= $virgula." r26_dtvenc = null ";
         $virgula = ",";
         if(trim($this->r26_dtvenc) == null ){ 
           $this->erro_sql = " Campo Data Vencimento do Evento nao Informado.";
           $this->erro_campo = "r26_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($r26_anousu!=null){
       $sql .= " r26_anousu = $this->r26_anousu";
     }
     if($r26_mesusu!=null){
       $sql .= " and  r26_mesusu = $this->r26_mesusu";
     }
     if($r26_regist!=null){
       $sql .= " and  r26_regist = $this->r26_regist";
     }
     if($r26_evento!=null){
       $sql .= " and  r26_evento = '$this->r26_evento'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r26_anousu,$this->r26_mesusu,$this->r26_regist,$this->r26_evento));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3889,'$this->r26_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3890,'$this->r26_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3891,'$this->r26_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3892,'$this->r26_evento','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r26_anousu"]))
           $resac = db_query("insert into db_acount values($acount,547,3889,'".AddSlashes(pg_result($resaco,$conresaco,'r26_anousu'))."','$this->r26_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r26_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,547,3890,'".AddSlashes(pg_result($resaco,$conresaco,'r26_mesusu'))."','$this->r26_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r26_regist"]))
           $resac = db_query("insert into db_acount values($acount,547,3891,'".AddSlashes(pg_result($resaco,$conresaco,'r26_regist'))."','$this->r26_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r26_evento"]))
           $resac = db_query("insert into db_acount values($acount,547,3892,'".AddSlashes(pg_result($resaco,$conresaco,'r26_evento'))."','$this->r26_evento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r26_dtinic"]))
           $resac = db_query("insert into db_acount values($acount,547,3893,'".AddSlashes(pg_result($resaco,$conresaco,'r26_dtinic'))."','$this->r26_dtinic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r26_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,547,3894,'".AddSlashes(pg_result($resaco,$conresaco,'r26_dtvenc'))."','$this->r26_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastramento dos Eventos                          nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r26_anousu."-".$this->r26_mesusu."-".$this->r26_regist."-".$this->r26_evento;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastramento dos Eventos                          nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r26_anousu."-".$this->r26_mesusu."-".$this->r26_regist."-".$this->r26_evento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r26_anousu."-".$this->r26_mesusu."-".$this->r26_regist."-".$this->r26_evento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r26_anousu=null,$r26_mesusu=null,$r26_regist=null,$r26_evento=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r26_anousu,$r26_mesusu,$r26_regist,$r26_evento));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3889,'$r26_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3890,'$r26_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3891,'$r26_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3892,'$r26_evento','E')");
         $resac = db_query("insert into db_acount values($acount,547,3889,'','".AddSlashes(pg_result($resaco,$iresaco,'r26_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,547,3890,'','".AddSlashes(pg_result($resaco,$iresaco,'r26_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,547,3891,'','".AddSlashes(pg_result($resaco,$iresaco,'r26_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,547,3892,'','".AddSlashes(pg_result($resaco,$iresaco,'r26_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,547,3893,'','".AddSlashes(pg_result($resaco,$iresaco,'r26_dtinic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,547,3894,'','".AddSlashes(pg_result($resaco,$iresaco,'r26_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from eventos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r26_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r26_anousu = $r26_anousu ";
        }
        if($r26_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r26_mesusu = $r26_mesusu ";
        }
        if($r26_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r26_regist = $r26_regist ";
        }
        if($r26_evento != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r26_evento = '$r26_evento' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastramento dos Eventos                          nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r26_anousu."-".$r26_mesusu."-".$r26_regist."-".$r26_evento;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastramento dos Eventos                          nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r26_anousu."-".$r26_mesusu."-".$r26_regist."-".$r26_evento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r26_anousu."-".$r26_mesusu."-".$r26_regist."-".$r26_evento;
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
        $this->erro_sql   = "Record Vazio na Tabela:eventos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r26_anousu,$this->r26_mesusu,$this->r26_regist,$this->r26_evento);
   }
   function sql_query ( $r26_anousu=null,$r26_mesusu=null,$r26_regist=null,$r26_evento=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from eventos ";
     $sql .= "      inner join historic  on  historic.r25_anousu = eventos.r26_anousu and  historic.r25_mesusu = eventos.r26_mesusu and  historic.r25_codigo = eventos.r26_evento";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = eventos.r26_anousu and  pessoal.r01_mesusu = eventos.r26_mesusu and  pessoal.r01_regist = eventos.r26_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  on  inssirf.r33_anousu = pessoal.r01_anousu and  inssirf.r33_mesusu = pessoal.r01_mesusu and  inssirf.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as b on   b.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as c on   c.r37_anousu = pessoal.r01_anousu and   c.r37_mesusu = pessoal.r01_mesusu and   c.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as d on   d.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu and   d.r37_mesusu = pessoal.r01_mesusu and   d.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r26_anousu!=null ){
         $sql2 .= " where eventos.r26_anousu = $r26_anousu "; 
       } 
       if($r26_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " eventos.r26_mesusu = $r26_mesusu "; 
       } 
       if($r26_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " eventos.r26_regist = $r26_regist "; 
       } 
       if($r26_evento!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " eventos.r26_evento = '$r26_evento' "; 
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
   function sql_query_file ( $r26_anousu=null,$r26_mesusu=null,$r26_regist=null,$r26_evento=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from eventos ";
     $sql2 = "";
     if($dbwhere==""){
       if($r26_anousu!=null ){
         $sql2 .= " where eventos.r26_anousu = $r26_anousu "; 
       } 
       if($r26_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " eventos.r26_mesusu = $r26_mesusu "; 
       } 
       if($r26_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " eventos.r26_regist = $r26_regist "; 
       } 
       if($r26_evento!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " eventos.r26_evento = '$r26_evento' "; 
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