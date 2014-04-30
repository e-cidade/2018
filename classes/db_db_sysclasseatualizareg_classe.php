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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_sysclasseatualizareg
class cl_db_sysclasseatualizareg { 
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
   var $codatualiza = 0; 
   var $ip = null; 
   var $codusu = 0; 
   var $dataalt_dia = null; 
   var $dataalt_mes = null; 
   var $dataalt_ano = null; 
   var $dataalt = null; 
   var $horaalt = null; 
   var $codarq = 0; 
   var $nomearq = null; 
   var $metodo = null; 
   var $fontenovo = null; 
   var $fonteoriginal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codatualiza = int4 = Código 
                 ip = varchar(50) = IP 
                 codusu = int4 = Código do usuário 
                 dataalt = date = Data da alteração 
                 horaalt = char(5) = Hora da alteração 
                 codarq = int4 = Codigo Arquivo 
                 nomearq = char(40) = Nome do Arquivo 
                 metodo = varchar(100) = Nome do método 
                 fontenovo = text = Fonte novo 
                 fonteoriginal = text = Fonte original 
                 ";
   //funcao construtor da classe 
   function cl_db_sysclasseatualizareg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysclasseatualizareg"); 
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
       $this->codatualiza = ($this->codatualiza == ""?@$GLOBALS["HTTP_POST_VARS"]["codatualiza"]:$this->codatualiza);
       $this->ip = ($this->ip == ""?@$GLOBALS["HTTP_POST_VARS"]["ip"]:$this->ip);
       $this->codusu = ($this->codusu == ""?@$GLOBALS["HTTP_POST_VARS"]["codusu"]:$this->codusu);
       if($this->dataalt == ""){
         $this->dataalt_dia = ($this->dataalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dataalt_dia"]:$this->dataalt_dia);
         $this->dataalt_mes = ($this->dataalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dataalt_mes"]:$this->dataalt_mes);
         $this->dataalt_ano = ($this->dataalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dataalt_ano"]:$this->dataalt_ano);
         if($this->dataalt_dia != ""){
            $this->dataalt = $this->dataalt_ano."-".$this->dataalt_mes."-".$this->dataalt_dia;
         }
       }
       $this->horaalt = ($this->horaalt == ""?@$GLOBALS["HTTP_POST_VARS"]["horaalt"]:$this->horaalt);
       $this->codarq = ($this->codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["codarq"]:$this->codarq);
       $this->nomearq = ($this->nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["nomearq"]:$this->nomearq);
       $this->metodo = ($this->metodo == ""?@$GLOBALS["HTTP_POST_VARS"]["metodo"]:$this->metodo);
       $this->fontenovo = ($this->fontenovo == ""?@$GLOBALS["HTTP_POST_VARS"]["fontenovo"]:$this->fontenovo);
       $this->fonteoriginal = ($this->fonteoriginal == ""?@$GLOBALS["HTTP_POST_VARS"]["fonteoriginal"]:$this->fonteoriginal);
     }else{
       $this->codatualiza = ($this->codatualiza == ""?@$GLOBALS["HTTP_POST_VARS"]["codatualiza"]:$this->codatualiza);
     }
   }
   // funcao para inclusao
   function incluir ($codatualiza){ 
      $this->atualizacampos();
     if($this->ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codusu == null ){ 
       $this->erro_sql = " Campo Código do usuário nao Informado.";
       $this->erro_campo = "codusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dataalt == null ){ 
       $this->erro_sql = " Campo Data da alteração nao Informado.";
       $this->erro_campo = "dataalt_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->horaalt == null ){ 
       $this->erro_sql = " Campo Hora da alteração nao Informado.";
       $this->erro_campo = "horaalt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codarq == null ){ 
       $this->erro_sql = " Campo Codigo Arquivo nao Informado.";
       $this->erro_campo = "codarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->nomearq == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->metodo == null ){ 
       $this->erro_sql = " Campo Nome do método nao Informado.";
       $this->erro_campo = "metodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fontenovo == null ){ 
       $this->erro_sql = " Campo Fonte novo nao Informado.";
       $this->erro_campo = "fontenovo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fonteoriginal == null ){ 
       $this->erro_sql = " Campo Fonte original nao Informado.";
       $this->erro_campo = "fonteoriginal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codatualiza == "" || $codatualiza == null ){
       $result = db_query("select nextval('db_sysclasseatualizareg_codatualiza_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_sysclasseatualizareg_codatualiza_seq do campo: codatualiza"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codatualiza = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_sysclasseatualizareg_codatualiza_seq");
       if(($result != false) && (pg_result($result,0,0) < $codatualiza)){
         $this->erro_sql = " Campo codatualiza maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codatualiza = $codatualiza; 
       }
     }
     if(($this->codatualiza == null) || ($this->codatualiza == "") ){ 
       $this->erro_sql = " Campo codatualiza nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysclasseatualizareg(
                                       codatualiza 
                                      ,ip 
                                      ,codusu 
                                      ,dataalt 
                                      ,horaalt 
                                      ,codarq 
                                      ,nomearq 
                                      ,metodo 
                                      ,fontenovo 
                                      ,fonteoriginal 
                       )
                values (
                                $this->codatualiza 
                               ,'$this->ip' 
                               ,$this->codusu 
                               ,".($this->dataalt == "null" || $this->dataalt == ""?"null":"'".$this->dataalt."'")." 
                               ,'$this->horaalt' 
                               ,$this->codarq 
                               ,'$this->nomearq' 
                               ,'$this->metodo' 
                               ,'$this->fontenovo' 
                               ,'$this->fonteoriginal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atualiza Classse ($this->codatualiza) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atualiza Classse já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atualiza Classse ($this->codatualiza) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codatualiza;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codatualiza));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9993,'$this->codatualiza','I')");
       $resac = db_query("insert into db_acount values($acount,1714,9993,'','".AddSlashes(pg_result($resaco,0,'codatualiza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,986,'','".AddSlashes(pg_result($resaco,0,'ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,9983,'','".AddSlashes(pg_result($resaco,0,'codusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,9985,'','".AddSlashes(pg_result($resaco,0,'dataalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,9986,'','".AddSlashes(pg_result($resaco,0,'horaalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,759,'','".AddSlashes(pg_result($resaco,0,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,760,'','".AddSlashes(pg_result($resaco,0,'nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,9990,'','".AddSlashes(pg_result($resaco,0,'metodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,9992,'','".AddSlashes(pg_result($resaco,0,'fontenovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1714,9991,'','".AddSlashes(pg_result($resaco,0,'fonteoriginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codatualiza=null) { 
      $this->atualizacampos();
     $sql = " update db_sysclasseatualizareg set ";
     $virgula = "";
     if(trim($this->codatualiza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codatualiza"])){ 
       $sql  .= $virgula." codatualiza = $this->codatualiza ";
       $virgula = ",";
       if(trim($this->codatualiza) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codatualiza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ip"])){ 
       $sql  .= $virgula." ip = '$this->ip' ";
       $virgula = ",";
       if(trim($this->ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codusu"])){ 
       $sql  .= $virgula." codusu = $this->codusu ";
       $virgula = ",";
       if(trim($this->codusu) == null ){ 
         $this->erro_sql = " Campo Código do usuário nao Informado.";
         $this->erro_campo = "codusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dataalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dataalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dataalt_dia"] !="") ){ 
       $sql  .= $virgula." dataalt = '$this->dataalt' ";
       $virgula = ",";
       if(trim($this->dataalt) == null ){ 
         $this->erro_sql = " Campo Data da alteração nao Informado.";
         $this->erro_campo = "dataalt_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dataalt_dia"])){ 
         $sql  .= $virgula." dataalt = null ";
         $virgula = ",";
         if(trim($this->dataalt) == null ){ 
           $this->erro_sql = " Campo Data da alteração nao Informado.";
           $this->erro_campo = "dataalt_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->horaalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["horaalt"])){ 
       $sql  .= $virgula." horaalt = '$this->horaalt' ";
       $virgula = ",";
       if(trim($this->horaalt) == null ){ 
         $this->erro_sql = " Campo Hora da alteração nao Informado.";
         $this->erro_campo = "horaalt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codarq"])){ 
       $sql  .= $virgula." codarq = $this->codarq ";
       $virgula = ",";
       if(trim($this->codarq) == null ){ 
         $this->erro_sql = " Campo Codigo Arquivo nao Informado.";
         $this->erro_campo = "codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomearq"])){ 
       $sql  .= $virgula." nomearq = '$this->nomearq' ";
       $virgula = ",";
       if(trim($this->nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->metodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["metodo"])){ 
       $sql  .= $virgula." metodo = '$this->metodo' ";
       $virgula = ",";
       if(trim($this->metodo) == null ){ 
         $this->erro_sql = " Campo Nome do método nao Informado.";
         $this->erro_campo = "metodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fontenovo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fontenovo"])){ 
       $sql  .= $virgula." fontenovo = '$this->fontenovo' ";
       $virgula = ",";
       if(trim($this->fontenovo) == null ){ 
         $this->erro_sql = " Campo Fonte novo nao Informado.";
         $this->erro_campo = "fontenovo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fonteoriginal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fonteoriginal"])){ 
       $sql  .= $virgula." fonteoriginal = '$this->fonteoriginal' ";
       $virgula = ",";
       if(trim($this->fonteoriginal) == null ){ 
         $this->erro_sql = " Campo Fonte original nao Informado.";
         $this->erro_campo = "fonteoriginal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codatualiza!=null){
       $sql .= " codatualiza = $this->codatualiza";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codatualiza));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9993,'$this->codatualiza','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codatualiza"]))
           $resac = db_query("insert into db_acount values($acount,1714,9993,'".AddSlashes(pg_result($resaco,$conresaco,'codatualiza'))."','$this->codatualiza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ip"]))
           $resac = db_query("insert into db_acount values($acount,1714,986,'".AddSlashes(pg_result($resaco,$conresaco,'ip'))."','$this->ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codusu"]))
           $resac = db_query("insert into db_acount values($acount,1714,9983,'".AddSlashes(pg_result($resaco,$conresaco,'codusu'))."','$this->codusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dataalt"]))
           $resac = db_query("insert into db_acount values($acount,1714,9985,'".AddSlashes(pg_result($resaco,$conresaco,'dataalt'))."','$this->dataalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["horaalt"]))
           $resac = db_query("insert into db_acount values($acount,1714,9986,'".AddSlashes(pg_result($resaco,$conresaco,'horaalt'))."','$this->horaalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codarq"]))
           $resac = db_query("insert into db_acount values($acount,1714,759,'".AddSlashes(pg_result($resaco,$conresaco,'codarq'))."','$this->codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomearq"]))
           $resac = db_query("insert into db_acount values($acount,1714,760,'".AddSlashes(pg_result($resaco,$conresaco,'nomearq'))."','$this->nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["metodo"]))
           $resac = db_query("insert into db_acount values($acount,1714,9990,'".AddSlashes(pg_result($resaco,$conresaco,'metodo'))."','$this->metodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fontenovo"]))
           $resac = db_query("insert into db_acount values($acount,1714,9992,'".AddSlashes(pg_result($resaco,$conresaco,'fontenovo'))."','$this->fontenovo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fonteoriginal"]))
           $resac = db_query("insert into db_acount values($acount,1714,9991,'".AddSlashes(pg_result($resaco,$conresaco,'fonteoriginal'))."','$this->fonteoriginal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atualiza Classse nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codatualiza;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atualiza Classse nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codatualiza;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codatualiza;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codatualiza=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codatualiza));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9993,'$codatualiza','E')");
         $resac = db_query("insert into db_acount values($acount,1714,9993,'','".AddSlashes(pg_result($resaco,$iresaco,'codatualiza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,986,'','".AddSlashes(pg_result($resaco,$iresaco,'ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,9983,'','".AddSlashes(pg_result($resaco,$iresaco,'codusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,9985,'','".AddSlashes(pg_result($resaco,$iresaco,'dataalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,9986,'','".AddSlashes(pg_result($resaco,$iresaco,'horaalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,759,'','".AddSlashes(pg_result($resaco,$iresaco,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,760,'','".AddSlashes(pg_result($resaco,$iresaco,'nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,9990,'','".AddSlashes(pg_result($resaco,$iresaco,'metodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,9992,'','".AddSlashes(pg_result($resaco,$iresaco,'fontenovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1714,9991,'','".AddSlashes(pg_result($resaco,$iresaco,'fonteoriginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysclasseatualizareg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codatualiza != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codatualiza = $codatualiza ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atualiza Classse nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codatualiza;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atualiza Classse nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codatualiza;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codatualiza;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysclasseatualizareg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codatualiza=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysclasseatualizareg ";
     $sql .= "      inner join db_sysarquivo  on  db_sysarquivo.codarq = db_sysclasseatualizareg.codarq";
     $sql2 = "";
     if($dbwhere==""){
       if($codatualiza!=null ){
         $sql2 .= " where db_sysclasseatualizareg.codatualiza = $codatualiza "; 
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
   function sql_query_file ( $codatualiza=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysclasseatualizareg ";
     $sql2 = "";
     if($dbwhere==""){
       if($codatualiza!=null ){
         $sql2 .= " where db_sysclasseatualizareg.codatualiza = $codatualiza "; 
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